<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 08.04.2018
 * Time: 14:06
 */

namespace vsetigoroda\vo\currency;

use Exception;

class Money
{
    const OP_ADD = 1;
    const OP_SUB = -1;
    /**
     * @var float
     */
    private $cost;
    /**
     * @var ICurrency
     */
    private $currency;
    /**
     * @var int
     */
    private $decimals = 2;
    /**
     * @var string
     */
    private $decPoint = ',';
    /**
     * @var string
     */
    private $thousandsSep = ' ';

    /**
     * Money constructor.
     * @param float $cost
     * @param ICurrency $currency
     */
    public function __construct($cost, ICurrency $currency)
    {
        $this->cost = abs($cost);
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return ICurrency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $s = $this->getCost();
        $s = number_format($s, $this->decimals, $this->decPoint, $this->thousandsSep);
        $s .= '&nbsp;';
        $s .= $this->getCurrency()->__toString();
        return $s;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param Money $money
     * @param int $multiplier
     * @return Money
     * @throws Exception
     */
    public function op(Money $money, $multiplier = 1)
    {
        if (!in_array($multiplier, [self::OP_ADD, self::OP_SUB])) {
            throw new Exception("Bad multiplier: $multiplier.");
        }
        if ($this->getCurrency() != $money->getCurrency()) {
            throw new Exception("Different currencies.");
        }
        $cost = $this->getCost();
        $cost += $money->getCost() * $multiplier;
        return new Money($cost, $this->getCurrency());
    }

    /**
     * @param Money $money
     * @return self
     * @throws Exception
     */
    public function add(Money $money)
    {
        $money = $this->op($money, self::OP_ADD);
        return $money;
    }

    /**
     * @param Money $money
     * @return Money
     * @throws Exception
     */
    public function sub(Money $money)
    {
        $money = $this->op($money, self::OP_SUB);
        return $money;
    }
}