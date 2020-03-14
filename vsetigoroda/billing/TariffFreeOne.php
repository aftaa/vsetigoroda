<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 08.04.2018
 * Time: 2:46
 */

namespace vsetigoroda\billing;


use Exception;
use vsetigoroda\services\MysqliDbService;
use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\currency\Ruble;

class TariffFreeOne extends AbstractTariff
{
    const ID = -1;

    /**
     * TariffFreeOne constructor.
     * @param int $id
     * @throws Exception
     */
    public function __construct($id = self::ID)
    {
        parent::__construct($id);
        $this->setName('freeone');
        $this->setTitle('FreeOne');
        $this->setMonthPrice(new Money(0, new Ruble));
        $this->setDayPrice(new Money(0, new Ruble()));
    }
}