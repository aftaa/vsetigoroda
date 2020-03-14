<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 09.04.2018
 * Time: 0:58
 */

namespace vsetigoroda\billing;

use DateInterval;
use Exception;
use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\DateTime;

class Account
{
    const NO_PAYMENT_DATE = -1;
    const NO_DAYS_LEFT = -1;
    /**
     * @var Money
     */
    private $balance;
    /**
     * @var AbstractTariff
     */
    private $tariff;
    /**
     * @var bool
     */
    private $dailyPaymentActivated = false;
    /**
     * @var DailyPaymentHistory[]
     */
    private $dailyPaymentHistory;

    /**
     * Account constructor.
     * @param Money $balance
     * @param AbstractTariff $tariff
     * @param bool $dailyPaymentActivated
     * @param DailyPaymentHistory[] $dailyPaymentHistory
     * @throws Exception
     */
    public function __construct(Money $balance, AbstractTariff $tariff, $dailyPaymentActivated, array $dailyPaymentHistory)
    {
        if (!isset($balance) || !isset($tariff) || !isset($dailyPaymentActivated) || !isset($dailyPaymentHistory)) {
            throw new Exception('Все параметры конструктора обязательны.');
        }
        $this->balance = $balance;
        $this->tariff = $tariff;
        $this->dailyPaymentActivated = $dailyPaymentActivated;
        $this->dailyPaymentHistory = $dailyPaymentHistory;
    }


    /**
     * @return Money
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param Money $balance
     */
    public function setBalance(Money $balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return DateTime|int
     */
    public function getNextPaymentDate()
    {
        $daysLeft = $this->getDaysLeft();
        if (self::NO_DAYS_LEFT == $daysLeft) {
            return self::NO_PAYMENT_DATE;
        }
        $date = new DateTime;
        $date = $date->add(DateInterval::createFromDateString("+ $daysLeft days"));
        return $date;
    }

    /**
     * @return int
     */
    public function getDaysLeft()
    {
        if ($this->getTariff()->getMonthPrice()->getCost()) {
            return floor($this->getBalance()->getCost() / $this->getTariff()->getDayPrice()->getCost());
        } else {
            return self::NO_DAYS_LEFT;
        }
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
     * @return bool
     */
    public function isDailyPaymentActivated()
    {
        return $this->dailyPaymentActivated;
    }

    /**
     * @param bool $dailyPaymentActivated
     */
    public function setDailyPaymentActivated($dailyPaymentActivated)
    {
        $this->dailyPaymentActivated = $dailyPaymentActivated;
    }

    /**
     * Был ли сегодня произведен суточный платеж?
     * @return bool
     */
    public function wasDailyPaymentToday()
    {
        if (count($this->dailyPaymentHistory) && $this->isDailyPaymentActivated()) {
            $today = new DateTime;
            return $this->getLastPaymentDate()->format('Y-m-d') == $today->format('Y-m-d');
        } else {
            return false;
        }
    }

    /**
     * @return DateTime
     */
    public function getLastPaymentDate()
    {
        return $this->dailyPaymentHistory[0]->getDatetime();
    }
}