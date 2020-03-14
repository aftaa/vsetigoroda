<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 08.04.2018
 * Time: 12:45
 */

namespace vsetigoroda\vo\currency;


class Ruble implements ICurrency
{
    const CODE = '&#x20BD;';

    /**
     * @return string
     */
    public function __toString()
    {
        return self::CODE;
    }
}