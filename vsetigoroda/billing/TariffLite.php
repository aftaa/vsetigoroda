<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 07.04.2018
 * Time: 23:54
 */

namespace vsetigoroda\billing;

use Exception;

class TariffLite extends AbstractTariff
{
    const ID = 1;

    /**
     * TariffLite constructor.
     * @param int $id
     * @throws Exception
     */
    public function __construct($id = self::ID)
    {
        parent::__construct($id);
    }
}