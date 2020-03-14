<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 22:51
 */

namespace vsetigoroda;

use Exception;
use mysqli;
use vsetigoroda\Company;
use vsetigoroda\services\MysqliDbService;

class CompanyMysqliRepository implements ICompanyRepository
{

    /**
     * @var MysqliDbService
     */
    public $db;

    /**
     * CompanyRepository constructor.
     */
    public function __construct()
    {
        $this->db = MysqliDbService::getInstance();
    }

    /**
     * @param int $id
     * @return \vsetigoroda\Company
     * @throws CompanyNotFoundException
     * @throws MemberNotFoundException
     * @throws billing\TariffNoOfferException
     */
    public function getCompanyById($id)
    {
        $query = "SELECT * FROM aw_com WHERE comid=?";
        $db = $this->db->getDb();
        $stmt = $db->prepare($query);
        if ($db->errno) {
            throw new Exception($db->error);
        }
        if (!$stmt->bind_param('i', $id) || !$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $result = $stmt->get_result();
        if (!$result->num_rows) {
            throw new CompanyNotFoundException("Company with ID=$id cannot be found.");
        }
        $row = $result->fetch_object();
        $company = new Company;
        $company->setId($row->comid);
        $company->setName($row->comname);
        $company->setOwner((new MemberMysqliRepository())->getMemberById($row->userid));
        return $company;
    }

    /**
     * @param Member $member
     * @return Company
     * @throws CompanyNotFoundException
     */
    public function getCompanyByMember(Member $member)
    {
        $query = "SELECT * FROM aw_com WHERE userid=?";
        $db = $this->db->getDb();
        $stmt = $db->prepare($query);
        if ($db->errno) {
            throw new Exception($db->error);
        }
        $memberId = $member->getId();
        if (!$stmt->bind_param('i', $memberId) || !$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $result = $stmt->get_result();
        if (!$result->num_rows) {
            throw new CompanyNotFoundException("Company with userid={$member->getId()} cannot be found.");
        }
        $row = $result->fetch_object();
        $company = new Company;
        $company->setId($row->comid);
        $company->setName($row->comname);
        $company->setOwner($member);
        return $company;
    }
}