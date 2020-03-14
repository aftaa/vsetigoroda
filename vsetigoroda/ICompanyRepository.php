<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 22:44
 */

namespace vsetigoroda;


interface ICompanyRepository
{
    /**
     * @param integer $id
     * @return Company
     */
    public function getCompanyById($id);
}