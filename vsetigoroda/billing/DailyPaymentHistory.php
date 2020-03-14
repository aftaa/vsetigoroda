<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 2:21
 */

namespace vsetigoroda\billing;

use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\DateTime;

class DailyPaymentHistory
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var AbstractTariff
     */
    private $tariff;
    /**
     * @var Money
     */
    private $perMonth;
    /**
     * @var Money
     *
     */
    private $perDay;
    /**
     * @var Money
     */
    private $balanceBefore;
    /**
     * @var Money
     */
    private $balanceAfter;
    /**
     * @var DateTime
     */
    private $datetime;

    /**
     * DailyPaymentHistory constructor.
     * @param AbstractTariff $tariff
     * @param Money $perMonth
     * @param Money $perDay
     * @param DateTime $datetime
     * @param Money $balanceBefore
     * @param Money $balanceAfter
     */
    public function __construct(AbstractTariff $tariff, Money $perMonth, Money $perDay, DateTime $datetime,
                                Money $balanceBefore, Money $balanceAfter)
    {
        $this->tariff = $tariff;
        $this->perMonth = $perMonth;
        $this->perDay = $perDay;
        $this->datetime = $datetime;
        $this->balanceBefore = $balanceBefore;
        $this->balanceAfter = $balanceAfter;
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return AbstractTariff
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * @param AbstractTariff $tariff
     */
    public function setTariff(AbstractTariff $tariff)
    {
        $this->tariff = $tariff;
    }

    /**
     * @return Money
     */
    public function getPerMonth()
    {
        return $this->perMonth;
    }

    /**
     * @param Money $perMonth
     */
    public function setPerMonth(Money $perMonth)
    {
        $this->perMonth = $perMonth;
    }

    /**
     * @return Money
     */
    public function getPerDay()
    {
        return $this->perDay;
    }

    /**
     * @param Money $perDay
     */
    public function setPerDay(Money $perDay)
    {
        $this->perDay = $perDay;
    }

    /**
     * @return DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param DateTime $datetime
     */
    public function setDatetime(DateTime $datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return Money
     */
    public function getBalanceBefore()
    {
        return $this->balanceBefore;
    }

    /**
     * @param Money $balanceBefore
     */
    public function setBalanceBefore($balanceBefore)
    {
        $this->balanceBefore = $balanceBefore;
    }

    /**
     * @return Money
     */
    public function getBalanceAfter()
    {
        return $this->balanceAfter;
    }

    /**
     * @param Money $balanceAfter
     */
    public function setBalanceAfter($balanceAfter)
    {
        $this->balanceAfter = $balanceAfter;
    }
}