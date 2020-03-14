<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 16:52
 */

namespace vsetigoroda;

use Exception;
use mysqli;
use stdClass;
use vsetigoroda\billing\Account;
use vsetigoroda\billing\DailyPaymentHistoryMysqliRepository;
use vsetigoroda\billing\TariffFree;
use vsetigoroda\billing\TariffMysqliRepository;
use vsetigoroda\billing\TariffNoOfferException;
use vsetigoroda\services\MysqliDbService;
use mail\WrongEmailAddressException;
use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\currency\Ruble;
use vsetigoroda\vo\DateTime;
use mail\Email;

class MemberMysqliRepository implements IMemberRepository
{
    /**
     * @var MysqliDbService
     */
    public $db;

    /**
     * MemberRepository constructor.
     */
    private $companyMysqliRepository;

    /**
     * @var TariffMysqliRepository
     */
    private $tariffMysqliRepository;

    /**
     * @var DailyPaymentHistoryMysqliRepository
     */
    private $paymentHistory;

    /**
     * MemberMysqliRepository constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->paymentHistory = new DailyPaymentHistoryMysqliRepository;
        $this->db = MysqliDbService::getInstance();
        $this->companyMysqliRepository = new CompanyMysqliRepository;
        $this->tariffMysqliRepository = new TariffMysqliRepository;
    }

    /**
     * @param int $id
     * @return Member
     * @throws MemberNotFoundException
     * @throws Exception
     */
    public function getMemberById($id)
    {
        $db = $this->db->getDb();
        $stmt = $db->prepare('SELECT * FROM aw_member WHERE userid=?');
        if ($db->errno) {
            throw new Exception($db->error);
        }
        if (!$stmt->bind_param('i', $id) || !$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $result = $stmt->get_result();
        if (!$result->num_rows) {
            throw new MemberNotFoundException("Member with ID=$id was not found.");
        }
        $row = $result->fetch_object();
        $member = $this->row2member($row);

        return $member;
    }

    /**
     * @param int $memberId
     * @return int
     * @throws Exception
     */
    public function numProducts($memberId)
    {
        if (empty($memberId)) {
            throw new Exception('Empty $memberId param.');
        }
//        $stmt = $this->db->prepare("SELECT COUNT(*) FROM aw_product WHERE userid=?");
        $memberId = (int)$memberId;
        $db = $this->db->getDbInfo();
        $stmt = $db->prepare("SELECT COUNT(*) FROM aw_info$memberId");
        if (!$stmt) {
            return 0;
            throw new Exception($db->error);
        }
        if (/*!$stmt->bind_param('i', $memberId) || */
        !$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $row = $stmt->get_result()->fetch_row();
        $num = $row[0];
        return $num;
    }

    /**
     * @param Member $member
     * @return int
     * @throws Exception
     */
    public function saveMember(Member $member)
    {
        $db = $this->db->getDb();
        // TODO пока сохраняет только баланс и тарифный план
        $query = "UPDATE aw_member SET money=?, tarif=? WHERE userid=?";
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception($db->error);
        }

        $balance = $member->getAccount()->getBalance()->getCost();
        $tariffName = $member->getAccount()->getTariff()->getName();
        $memberId = $member->getId();
        if (!$stmt->bind_param('dsi', $balance,$tariffName, $memberId) || !$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        return $db->affected_rows;
    }

    /**
     * @return Member[]
     * @throws Exception
     */
    public function getAll()
    {
        $members = [];
        $query = "SELECT * FROM aw_member";
        $result = $this->db->getDb()->query($query);
        if (!$result) {
            throw new Exception($this->db->getDb()->error);
        }
        while ($row = $result->fetch_object()) {
            $members[] = $this->row2member($row);
        }
        return $members;
    }

    /**
     * @param stdClass $row
     * @return Member
     * @throws Exception
     */
    private function row2member(stdClass $row)
    {
        $member = new Member;
        /* Get the tariff */
        try {
            $tariff = $this->tariffMysqliRepository->getTariff($row->tarif);
        } catch (TariffNoOfferException $e) {
            $tariff = new TariffFree;
        }

        try {
            $member->setEmail(new Email($row->email));
        } catch (WrongEmailAddressException $e) {
            // TODO письмо админу что вот тут вот очень плохой e-mail.
        }
        $member
            ->setName($row->username)
            ->setId($row->userid);
        /* Get the account data */

        $ruble = new Ruble;
        $member->setAccount(new Account(
                new Money($row->money, $ruble),
                $tariff, $row->daily_payment_activated,
                $this->paymentHistory->getLog(
                    $member,
                    $this->tariffMysqliRepository->getTariffs()
                )
            )
        );

        /* Get the company */
        try {
            $company = $this->companyMysqliRepository->getCompanyByMember($member);
            $member->setCompany($company);
        } catch (CompanyNotFoundException $e) {
            // TODO
        }
        return $member;
    }

}