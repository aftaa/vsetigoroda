<?php

namespace vsetigoroda\billing;

use Exception;
use mysqli;
use vsetigoroda\services\MysqliDbService;
use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\currency\Ruble;

class TariffMysqliRepository implements ITariffRepository
{
    /**
     * @var MysqliDbService
     */
    public $db;

    /**
     * @var AbstractTariff[]
     */
    private $tariffs;

    /**
     * TariffMysqliRepository constructor.
     * @param bool $setPrevNext
     * @throws Exception
     */
    public function __construct($setPrevNext = true)
    {
        $this->db = MysqliDbService::getInstance();
        $this->loadTariffs($setPrevNext);
    }

    /**
     * @param AbstractTariff $tariff
     * @return void
     * @throws Exception
     */
    public function loadDataInto(AbstractTariff $tariff)
    {
        $tariffId = $tariff->getId();

        $query = 'SELECT * FROM aw_tarif WHERE id=?';
        $db = $this->db->getDb();
        $stmt = $db->prepare($query);
        if (!$stmt) {
            throw new Exception($db->error);
        }
        if (!$stmt->bind_param('i', $tariffId) || !$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $row = $stmt->get_result()->fetch_object();
        $tariff->setName($row->name);
        $tariff->setTitle($row->rus_name);
        $tariff->setMonthPrice(new Money($row->coast, new Ruble));
        $tariff->setDayPrice(new Money($row->cday, new Ruble));
        $tariff->setNumVisibleProductsFrom($row->obv);
        $tariff->setNumTopDays($row->topsut);
        $tariff->setAllowedYml((bool)$row->xml);
        $tariff->setEnable((bool)$row->enable);
    }

    /**
     * @param bool $setPrevNext
     * @throws Exception
     */
    public function loadTariffs($setPrevNext)
    {
        $free = new TariffFree;
        $lite = new TariffLite;
        $start = new TariffStart;
        $premium = new TariffPremium;
        $maxi = new TariffMaxi;
        $freeOne = new TariffFreeOne;

        $this->loadDataInto($lite);
        $this->loadDataInto($start);
        $this->loadDataInto($premium);
        $this->loadDataInto($maxi);

        if ($setPrevNext) {
            $free->setNextTariff($lite);
            $lite->setNextTariff($start);
            $start->setNextTariff($premium);
            $premium->setNextTariff($maxi);

            $lite->setPrevTariff($free);
            $start->setPrevTariff($lite);
            $premium->setPrevTariff($start);
            $maxi->setPrevTariff($premium);
        }

        $this->tariffs[$free->getId()] = $free;
        $this->tariffs[$free->getName()] = $free;
        $this->tariffs[$lite->getId()] = $lite;
        $this->tariffs[$lite->getName()] = $lite;
        $this->tariffs[$start->getId()] = $start;
        $this->tariffs[$start->getName()] = $start;
        $this->tariffs[$premium->getId()] = $premium;
        $this->tariffs[$premium->getName()] = $premium;
        $this->tariffs[$maxi->getId()] = $maxi;
        $this->tariffs[$maxi->getName()] = $maxi;
        $this->tariffs[$freeOne->getId()] = $freeOne;
        $this->tariffs[$freeOne->getName()] = $freeOne;
    }

    /**
     * @param int|string|null $idOrName
     * @return AbstractTariff
     * @throws TariffNoOfferException
     */
    public function getTariff($idOrName = null)
    {
        if (null === $idOrName) {
            throw new Exception('Param $idOrName is empty.');
        } elseif (!is_string($idOrName) && !is_integer($idOrName)) {
            throw new Exception('Param $idOrName has a bad type.');
        } elseif (!isset($this->tariffs[$idOrName])) {
            throw new TariffNoOfferException("Tariff `$idOrName` not found.");
        } else {
            return $this->tariffs[$idOrName];
        }
    }

    /**
     * @return AbstractTariff[]
     */
    public function getTariffs()
    {
        $tariffs = [];
        foreach ($this->tariffs as $key => $tariff) {
            if (ctype_alpha($key)) {
                $tariffs[$key] = $tariff;
            }
        }
        return $tariffs;
    }
}