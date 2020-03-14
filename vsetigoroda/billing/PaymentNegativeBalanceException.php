<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 13.04.2018
 * Time: 3:17
 */

namespace vsetigoroda\billing;

use Exception;
use Throwable;

class PaymentNegativeBalanceException extends PaymentException
{
    public function __construct($message = Payment::RESULT_NEGATIVE_BALANCE, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}