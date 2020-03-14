<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 08.04.2018
 * Time: 2:03
 */

namespace vsetigoroda\billing;

use Exception;

class TariffFree extends AbstractTariff
{
    const ID = 0;

    /**
     * TariffFree constructor.
     * @param int $id
     * @throws Exception
     */
    public function __construct($id = self::ID)
    {
        parent::__construct($id);
        $this->setName('free');
        $this->setTitle('Начало');
        $this->setAllowedYml(false);
    }
}