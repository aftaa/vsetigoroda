<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 08.04.2018
 * Time: 1:50
 */

namespace vsetigoroda\billing;

use Exception;
use vsetigoroda\Member;

class TariffFinder
{
    /**
     * @var ITariffRepository
     */
    private $tariffRepository;

    /**
     * TariffFinder constructor.
     * @param ITariffRepository $repository
     * @throws Exception
     */
    public function __construct(ITariffRepository $repository)
    {
        if (!$repository) {
            throw new Exception('Param $repossitory is empty.');
        }
        $this->tariffRepository = $repository;
    }

    /**
     * @param int $numProducts
     * @return AbstractTariff
     * @throws TariffNoOfferException
     */
    public function findOffer($numProducts)
    {
        foreach ($this->tariffRepository->getTariffs() as $tariff) {
            if ($numProducts >= $tariff->getNumVisibleProductsFrom()
                && ($numProducts <= $tariff->getNumVisibleProductsTo() || !$tariff->getNumVisibleProductsTo())) {
                return $tariff;
            }
        }
        throw new TariffNoOfferException("Cannot get offer for \$numProducts=$numProducts");
    }
}