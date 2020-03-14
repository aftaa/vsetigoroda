<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 13.04.2018
 * Time: 2:55
 */

namespace vsetigoroda\billing;

use Exception;
use Throwable;

class PaymentMoneyNotEnoughException extends PaymentException
{
    /**
     * PaymentMoneyNotEnoughException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(Payment::RESULT_MONEY_NOT_ENOUGH, $code, $previous);
    }
}