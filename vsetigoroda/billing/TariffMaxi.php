<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 08.04.2018
 * Time: 0:01
 */

namespace vsetigoroda\billing;

use Exception;

class TariffMaxi extends AbstractTariff
{
    const ID = 4;

    /**
     * TariffMaxi constructor.
     * @param int $id
     * @throws Exception
     */
    public function __construct($id = self::ID)
    {
        parent::__construct($id);
    }
}