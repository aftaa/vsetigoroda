<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 16:35
 */

namespace vsetigoroda;
use vsetigoroda\Member;

interface IMemberRepository
{
    /**
     * @param int $id
     * @return Member|bool
     */
    public function getMemberById($id);

    /**
     * @param int $id
     * @return int
     */
    public function numProducts($id);

    /**
     * @return Member[]
     */
    public function getAll();
}