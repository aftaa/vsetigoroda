<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 08.04.2018
 * Time: 3:23
 */

namespace vsetigoroda\billing;

use Exception;

class TariffCompare
{
    const LESS = -1;

    const GRATER = 1;

    const EQ = 0;

    public static $labels = [
        self::LESS => 'Новый тариф дешевле',
        self::GRATER => 'Новый тариф дороже',
        self::EQ => 'Новый тариф такой же',
    ];

    /**
     * @param AbstractTariff $tariff
     * @param AbstractTariff $newTariff
     * @return int
     * @throws Exception
     */
    public static function cmp(AbstractTariff $tariff, AbstractTariff $newTariff)
    {
        if ($tariff == $newTariff) {
            return self::EQ;
        }
        do {
            $prevTariff = $tariff->getPrevTariff();
            if ($newTariff == $prevTariff) {
                return self::LESS;
            }
        } while ($prevTariff);
        do {
            $nextTariff = $tariff->getNextTariff();
            if ($newTariff == $nextTariff) {
                return self::GREATER;
            }
        } while($nextTariff);
        throw new Exception('Error comparing triffs.');
    }

    /**
     * @param AbstractTariff $tariff
     * @param AbstractTariff $newTariff
     * @return string
     * @throws Exception
     */
    public static function cmpGetName(AbstractTariff $tariff, AbstractTariff $newTariff)
    {
        $cmpResult = self::cmp($tariff, $newTariff);
        return self::$labels[$cmpResult];
    }
}