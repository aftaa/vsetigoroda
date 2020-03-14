<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 0:20
 */

namespace vsetigoroda\billing;


use Exception;
use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\currency\Ruble;
use vsetigoroda\vo\DateTime;

class DailyPaymentReport
{
    /**
     * @var DailyPaymentReportRow[]
     */
    private $rows = [];
    /**
     * @var DateTime
     */
    private $datetime;
    /**
     * @var Money
     */
    private $totalPaid;

    /**
     * DailyPaymentReport constructor.
     * @param DateTime|null $dateTime
     */
    public function __construct(DateTime $dateTime = null)
    {
        $this->datetime = $dateTime ? $dateTime : new DateTime;
        $this->totalPaid = new Money(0, new Ruble);
    }

    /**
     * @param DailyPaymentReportRow $row
     * @throws Exception
     */
    public function addRow(DailyPaymentReportRow $row)
    {
        $this->rows[] = $row;
        if (Payment::RESULT_OK === $row->getOperationCode()) {
            $this->totalPaid = $this->totalPaid->add($row->getTariffPerDay());
        }
    }

    /**
     * @return DailyPaymentReportRow[]
     */
    public function getRows()
    {
        return $this->rows;
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
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return Money
     */
    public function getTotalPaid()
    {
        return $this->totalPaid;
    }

    /**
     * @param Money $totalPaid
     */
    public function setTotalPaid($totalPaid)
    {
        $this->totalPaid = $totalPaid;
    }
}