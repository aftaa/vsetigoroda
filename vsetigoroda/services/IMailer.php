<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 16:06
 */

namespace vsetigoroda\services;

use mail\EmailAddress;

interface IMailer
{
    /**
     * @param string $subject
     * @return IMailer
     */
    public function setSubject($subject);

    /**
     * @param string $text
     * @return IMailer
     */
    public function setBodyText($text);

    /**
     * @param \mail\EmailAddress $emailAddress
     * @return IMailer
     */
    public function setFrom(EmailAddress $emailAddress);

    /**
     * @param EmailAddress $emailAddress
     * @return IMailer
     */
    public function addTo(EmailAddress $emailAddress);

    /**
     * @return bool
     */
    public function send();
}