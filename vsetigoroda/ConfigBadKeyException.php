<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 13.04.2018
 * Time: 3:44
 */

namespace vsetigoroda;

use Exception;
use Throwable;

class ConfigBadKeyException extends Exception
{
    /**
     * ConfigBadKeyException constructor.
     * @param $key
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($key, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Config key isn't exists: $key";
        parent::__construct($message, $code, $previous);
    }
}