<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 22:01
 */

namespace vsetigoroda\services;
require_once dirname(__FILE__) . '/../../include/cont.phpmailer.php';

use Exception;
use PHPMailer;
use phpmailerException;
use mail\EmailAddress;

class Mailer implements IMailer
{
    /**
     * @var PHPMailer
     */
    private $mailer;

    /**
     * Mailer constructor.
     */
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
    }

    /**
     * @param string $subject
     * @return IMailer
     */
    public function setSubject($subject)
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    /**
     * @param string $text
     * @return IMailer
     */
    public function setBodyText($text)
    {
        $this->mailer->Body = $text;
        return $this;
    }

    /**
     * @param EmailAddress $emailAddress
     * @return $this|IMailer
     * @throws phpmailerException
     */
    public function setFrom(EmailAddress $emailAddress)
    {
        $this->mailer->SetFrom($emailAddress->getEmail(), $emailAddress->getName());
        return $this;
    }

    /**
     * @param EmailAddress $emailAddress
     * @return IMailer
     */
    public function addTo(EmailAddress $emailAddress)
    {
        $this->mailer->AddAddress($emailAddress->getEmail(), $emailAddress->getName());
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function send()
    {
        return $this->mailer->Send();
    }
}