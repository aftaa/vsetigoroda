<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 0:20
 */

namespace vsetigoroda\billing;

use vsetigoroda\vo\currency\Money;

class DailyPaymentReportRow
{
    /**
     * @var int
     */
    private $memberId;
    /**
     * @var string
     */
    private $memberName;
    /**
     * @var string
     */
    private $tariffName;
    /**
     * @var Money
     */
    private $tariffPerDay;
    /**
     * @var Money
     */
    private $balanceBefore;
    /**
     * @var Money
     */
    private $balanceAfter;
    /**
     * @var string
     */
    private $operationResult;
    /**
     * @var int
     */
    private $operationCode;

    /**
     * DailyPaymentReportRow constructor.
     * @param int $memberId
     * @param string $memberName
     * @param string $tariffName
     * @param Money $tariffPerDay
     * @param Money $balanceBefore
     * @param Money $balanceAfter
     * @param string $operationResult
     * @param int $operationCode
     */
    public function __construct($memberId, $memberName, $tariffName, Money $tariffPerDay,
                                Money $balanceBefore, Money $balanceAfter, $operationResult, $operationCode)
    {
        $this->memberId = $memberId;
        $this->memberName = $memberName;
        $this->tariffName = $tariffName;
        $this->tariffPerDay = $tariffPerDay;
        $this->balanceBefore = $balanceBefore;
        $this->balanceAfter = $balanceAfter;
        $this->operationResult = $operationResult;
        $this->operationCode = $operationCode;
    }

    /**
     * @return int
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * @param int $memberId
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     * @return string
     */
    public function getMemberName()
    {
        return $this->memberName;
    }

    /**
     * @param string $memberName
     */
    public function setMemberName($memberName)
    {
        $this->memberName = $memberName;
    }

    /**
     * @return string
     */
    public function getTariffName()
    {
        return $this->tariffName;
    }

    /**
     * @param string $tariffName
     */
    public function setTariffName($tariffName)
    {
        $this->tariffName = $tariffName;
    }

    /**
     * @return float
     */
    public function getTariffPerDay()
    {
        return $this->tariffPerDay;
    }

    /**
     * @param float $tariffPerDay
     */
    public function setTariffPerDay($tariffPerDay)
    {
        $this->tariffPerDay = $tariffPerDay;
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

    /**
     * @return string
     */
    public function getOperationResult()
    {
        return $this->operationResult;
    }

    /**
     * @param string $operationResult
     */
    public function setOperationResult($operationResult)
    {
        $this->operationResult = $operationResult;
    }

    /**
     * @return int
     */
    public function getOperationCode()
    {
        return $this->operationCode;
    }

    /**
     * @param int $operationCode
     */
    public function setOperationCode($operationCode)
    {
        $this->operationCode = $operationCode;
    }
}
