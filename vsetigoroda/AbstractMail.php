<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 14.04.2018
 * Time: 23:57
 */

namespace vsetigoroda;


use vsetigoroda\services\TechAdminMailer;
use vsetigoroda\services\IMailer;
use mail\Email;
use mail\EmailAddress;

abstract class AbstractMail
{
    /**
     * @return EmailAddress
     */
    abstract public function getTo();

    /**
     * @return EmailAddress
     */
    abstract public function getFrom();

    /**
     * @return string
     */
    abstract public function getSubject();

    /**
     * @param bool $asHtml
     * @return string
     */
    abstract public function getMessage($asHtml);

    /**
     * @param IMailer $mailer
     * @param bool $asHtml
     * @return bool
     */
    public function send(IMailer $mailer, $asHtml = false)
    {
        return $mailer
            ->addTo($this->getTo())
            ->setFrom($this->getFrom())
            ->setSubject($this->getSubject())
            ->setBodyText($this->getMessage($asHtml))
            ->send();
    }

    /**
     * @param TechAdminMailer $mailer
     * @param bool $asHtml
     * @return bool
     */
    public function sendToTechAdmin(TechAdminMailer $mailer, $asHtml = false)
    {
        return $mailer
            ->setSubject($this->getSubject())
            ->setBodyText($this->getMessage($asHtml))
            ->send();
    }
}
