<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 2:24
 */

namespace vsetigoroda\billing;

use vsetigoroda\Member;

interface IDailyPaymentHistoryRepository
{
    /**
     * @param Member $member
     * @return DailyPaymentHistory[]
     */
    public function getLog(Member $member);

    /**
     * @param Member $member
     * @param DailyPaymentHistory $history
     * @return int
     */
    public function addLog(Member $member, DailyPaymentHistory $history);
}