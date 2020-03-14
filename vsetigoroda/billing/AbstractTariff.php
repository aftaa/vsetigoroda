<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 07.04.2018
 * Time: 22:04
 */

namespace vsetigoroda\billing;

use Exception;
use vsetigoroda\vo\currency\Money;
use vsetigoroda\vo\currency\Ruble;

class AbstractTariff
{
    /**
     * @var int
     */
    private $id = 0;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $title;
    /**
     * @var Money
     */
    private $monthPrice;
    /**
     * @var Money
     */
    private $dayPrice;
    /**
     * @var int
     */
    private $numVisibleProductsFrom = 0;
    /**
     * @var int
     */
    private $numVisibleProductsTo = null;
    /**
     * @var int
     */
    private $numTopDays = 0;
    /**
     * @var bool
     */
    private $allowedYml = true;
    /**
     * @var bool
     */
    private $enable = true;
    /**
     * @var null|self
     */
    private $prevTariff = null;
    /**
     * @var null|self
     */
    private $nextTariff = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * AbstractTariff constructor.
     * @param null $id
     * @throws Exception
     */
    public function __construct($id)
    {
        $this->setId($id);
        $this->monthPrice = new Money(0,new Ruble);
        $this->dayPrice = new Money(0, new Ruble);
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function setId($id = null)
    {
        if (null === $id) {
            throw new Exception('Param $id is NULL.');
        }
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Money
     */
    public function getMonthPrice()
    {
        return $this->monthPrice;
    }

    /**
     * @param Money $monthPrice
     */
    public function setMonthPrice(Money $monthPrice)
    {
        $this->monthPrice = $monthPrice;
    }

    /**
     * @return Money
     */
    public function getDayPrice()
    {
        return $this->dayPrice;
    }

    /**
     * @param Money $dayPrice
     */
    public function setDayPrice(Money $dayPrice)
    {
        $this->dayPrice = $dayPrice;
    }

    /**
     * @return int
     */
    public function getNumTopDays()
    {
        return $this->numTopDays;
    }

    /**
     * @param int $numTopDays
     */
    public function setNumTopDays($numTopDays)
    {
        $this->numTopDays = $numTopDays;
    }

    /**
     * @return bool
     */
    public function isAllowedYml()
    {
        return $this->allowedYml;
    }

    /**
     * @param bool $allowedYml
     */
    public function setAllowedYml($allowedYml)
    {
        $this->allowedYml = $allowedYml;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }

    /**
     * @return AbstractTariff|null
     */
    public function getPrevTariff()
    {
        return $this->prevTariff;
    }

    /**
     * @param self $prevTariff
     * @throws Exception
     */
    public function setPrevTariff(self $prevTariff)
    {
        if (!$prevTariff) {
            throw new Exception('Param $prevTariff is empty.');
        }
        $this->prevTariff = $prevTariff;
    }

    /**
     * @return AbstractTariff|null
     */
    public function getNextTariff()
    {
        return $this->nextTariff;
    }

    /**
     * @param self $nextTariff
     * @throws Exception
     */
    public function setNextTariff(self $nextTariff)
    {
        if (!$nextTariff) {
            throw new Exception('Param $nextTariff is empty.');
        }
        $this->nextTariff = $nextTariff;
        $this->setNumVisibleProductsTo($nextTariff->getNumVisibleProductsFrom() - 1);
    }

    /**
     * @return int
     */
    public function getNumVisibleProductsFrom()
    {
        return $this->numVisibleProductsFrom;
    }

    /**
     * @param int $numVisibleProductsFrom
     */
    public function setNumVisibleProductsFrom($numVisibleProductsFrom)
    {
        $this->numVisibleProductsFrom = $numVisibleProductsFrom;
    }

    /**
     * @return int
     */
    public function getNumVisibleProductsTo()
    {
        return $this->numVisibleProductsTo;
    }

    /**
     * @param int $numVisibleProductsTo
     */
    public function setNumVisibleProductsTo($numVisibleProductsTo)
    {
        $this->numVisibleProductsTo = $numVisibleProductsTo;
    }
}