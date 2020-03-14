<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 1:34
 */

namespace vsetigoroda\services;


interface ITechAdminMailer
{
    /**
     * @param string $subject
     * @return ITechAdminMailer
     */
    public function setSubject($subject);

    /**
     * @param string $message
     * @param bool $asHtml
     * @return mixed
     */
    public function setBodyText($message);

    /**
     * @return bool
     */
    public function send();
}