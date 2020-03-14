<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 21:00
 */

namespace vsetigoroda;


interface IConfigRepository
{
    /**
     * @return array
     */
    public function read();
}