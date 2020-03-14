<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 16:36
 */

namespace vsetigoroda;

use Exception;
use vsetigoroda\billing\AbstractTariff;
use vsetigoroda\billing\Account;
use mail\Email;

class Member
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var \mail\Email
     */
    private $email;
    /**
     * @var Company
     */
    private $company;
    /**
     * @var Account
     */
    private $account;

    /**
     * @param AbstractTariff $tariff
     * @return bool
     */
    public function canChangeTariffTo(AbstractTariff $tariff)
    {
        return $this->getAccount()->getBalance()->getCost() >= $tariff->getMonthPrice()->getCost();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Member
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Member
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \mail\Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return Member
     */
    public function setEmail(Email $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return AbstractTariff
     */
    public function getTariff()
    {
        return $this->getAccount()->getTariff();
    }

    /**
     * @param AbstractTariff $tariff
     * @return self
     * @throws Exception
     */
    public function setTariff(AbstractTariff $tariff)
    {
        if (!$this->getAccount()) {
            throw new Exception("PLEASE set Account first!");
        }
        $this->getAccount()->setTariff($tariff);
        return $this;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return self
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return self
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        return $this;
    }
}