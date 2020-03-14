<?php

namespace vsetigoroda\billing;

use vsetigoroda\billing\AbstractTariff;

interface ITariffRepository
{
    /**
     * @param AbstractTariff $tariff
     * @param bool $setPrevNext
     */
    public function loadDataInto(AbstractTariff $tariff);

    /**
     * @param bool $setPrevNext
     */
    public function loadTariffs($setPrevNext);

    /**
     * @param $idOrName
     * @return AbstractTariff
     */
    public function getTariff($idOrName);

    /**
     * @return AbstractTariff[]
     */
    public function getTariffs();
}