<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 07.04.2018
 * Time: 23:56
 */

namespace vsetigoroda\billing;

use Exception;

class TariffStart extends AbstractTariff
{
    const ID = 2;

    /**
     * TariffStart constructor.
     * @param int $id
     * @throws Exception
     */
    public function __construct($id = self::ID)
    {
        parent::__construct($id);
    }
}