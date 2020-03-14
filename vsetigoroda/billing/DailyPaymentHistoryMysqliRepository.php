<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 2:25
 */

namespace vsetigoroda\billing;

use Exception;
use mysqli;
use vsetigoroda\Member;
use vsetigoroda\services\MysqliDbService;
use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\currency\Ruble;
use vsetigoroda\vo\DateTime;

class DailyPaymentHistoryMysqliRepository implements IDailyPaymentHistoryRepository
{
    /**
     * @var mysqli
     */
    private $db;

    /**
     * DailyPaymentHistoryMysqliRepository constructor.
     * @throws Exception
     */
    private $tariffRepository;

    public function __construct()
    {
        $this->tariffRepository = new TariffMysqliRepository;
        $this->db = MysqliDbService::getInstance()->getDb();
    }

    /**
     * @param Member $member
     * @return DailyPaymentHistory[]
     * @throws Exception
     */
    public function getLog(Member $member)
    {
        $tariffs = $this->tariffRepository->getTariffs();

        $history = [];
        $query = "SELECT * FROM aw_daily_payment_log WHERE member_id=? ORDER BY datetime DESC";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception($this->db->error);
        }
        $memberId = $member->getId();
        if (!$stmt->bind_param('i', $memberId) || !$stmt->execute()) {
            throw new Exception($this->db->error);
        }
        $result = $stmt->get_result();
        $ruble = new Ruble;


        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            $history[] = new DailyPaymentHistory(
                $tariffs[$row['tariff_name']],
                new Money($row['per_month'], $ruble),
                new Money($row['per_day'], $ruble),
                new DateTime($row['datetime']),
                new Money($row['balance_before'], $ruble),
                new Money($row['balance_after'], $ruble)
            );
        }
        return $history;
    }

    /**
     * @param Member $member
     * @param DailyPaymentHistory $history
     * @return int
     * @throws Exception
     */
    public function addLog(Member $member, DailyPaymentHistory $history)
    {
        $query = "INSERT INTO aw_daily_payment_log SET member_id=?, tariff_name=?, per_month=?, per_day=?, datetime=?,
                  balance_before=?, balance_after=?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception($this->db->error);
        }
        $memberId = $member->getId();
        $tariffName = $history->getTariff()->getName();
        $perMonth = $history->getTariff()->getMonthPrice()->getCost();
        $perDay = $history->getTariff()->getDayPrice()->getCost();
        $datetime = $history->getDatetime()->toISO();
        $balanceBefore = $history->getBalanceBefore()->getCost();
        $balanceAfter = $history->getBalanceAfter()->getCost();

        if (!$stmt->bind_param('isddsdd', $memberId, $tariffName, $perMonth, $perDay, $datetime,
                $balanceBefore, $balanceAfter) || !$stmt->execute()) {
            echo '<pre>'; print_r([$memberId, $tariffName, $perMonth, $perDay, $datetime,
                $balanceBefore, $balanceAfter]); echo '</pre>';
            throw new Exception($stmt->error);
        }

        return $this->db->insert_id;
    }
}