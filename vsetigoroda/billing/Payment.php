<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 13.04.2018
 * Time: 2:39
 */

namespace vsetigoroda\billing;

use Exception;
use vsetigoroda\Member;
use vsetigoroda\vo\currency\Money;

class Payment
{
    const RESULT_OK = 0;
    const RESULT_NEGATIVE_BALANCE = 1;
    const RESULT_MONEY_NOT_ENOUGH = 2;
    public static $availableResults = [
        self::RESULT_OK => 'Успешно',
        self::RESULT_NEGATIVE_BALANCE => 'Отрицательный баланс',
        self::RESULT_MONEY_NOT_ENOUGH => 'Недостаточно средств',
    ];
    /**
     * @var Member
     */
    private $member;
    /**
     * @var Money
     */
    private $sum;
    /**
     * @var int
     */
    private $result;

    /**
     * IncomingPayment constructor.
     * @param Member $member
     * @param Money $sum
     */
    public function __construct(Member $member, Money $sum)
    {
        $this->member = $member;
        $this->sum = $sum;
    }

    /**
     * @throws PaymentMoneyNotEnoughException
     * @throws PaymentNegativeBalanceException
     */
    public function apply()
    {
        $account = $this->member->getAccount();
        $balance = $account->getBalance();
        if ($balance->getCost() < 0) {
            $this->setResult(self::RESULT_NEGATIVE_BALANCE);
            throw new PaymentNegativeBalanceException("Отрицательный баланс пользователя {$this->member->getName()}.");
            // TODO: здесь в очередь на рассылку отрицательный баланс или сразу письмо или в экспешнене обрабатывать
            // TODO: не правильней ли проверять метод в классе счет?
        }
        if ($balance->getCost() - $this->sum->getCost() < 0) {
            $this->setResult(self::RESULT_MONEY_NOT_ENOUGH);
            throw new PaymentMoneyNotEnoughException("Недостаточно средств на счете пользователя " .
                "{$this->member->getName()}: {$account->getBalance()} (попытка снять {$this->sum})");
        }
        //echo '<pre>'; print_r($balance->getCost()); echo '</pre>'; die;
        $this->setResult(self::RESULT_OK);
        $account->setBalance($balance->sub($this->sum));
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @return Money
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param $result
     * @throws Exception
     */
    public function setResult($result)
    {
        if (!array_key_exists($result, self::$availableResults)) {
            throw new Exception("Неверное значение результата операции: $result.");
        }
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getResultName()
    {
        $name = self::$availableResults[$this->getResult()];
        return $name;
    }
}