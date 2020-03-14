<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 15:51
 */

namespace vsetigoroda;

use Exception;
use mail\EmailAddress;
use vsetigoroda\services\IMailer;

class MailToMember extends AbstractMail
{
    /**
     * @var array|string
     */
    private $message = [
        'Вы получили сообщение от пользователя %SENDER_NAME%:',
        '',
        '%MESSAGE%',
        '',
        'Чтобы ответить, войдите в Ваш личный кабинет по ссылке:',
        'http://vseti-goroda.ru/member.php',
        '',
        '%SIGNATURE%',
    ];
    /**
     * @var string
     */
    private $subject = 'Новое личное сообщение на сайте vseti-goroda.ru';
    /**
     * @var \mail\EmailAddress
     */
    private $to;
    /**
     * @var EmailAddress
     */
    private $from;

    /**
     * EmailToMember constructor.
     * @param \mail\EmailAddress $to
     * @param EmailAddress $from
     * @param string $senderName
     * @param string $message
     * @param string $signature
     * @throws Exception
     */
    public function __construct(EmailAddress $to, EmailAddress $from, $senderName, $message, $signature)
    {
        if (!$to || !$from || !$senderName || !$message || !$signature) {
            throw new Exception('Заданы не все обязательные параметры');
        }
        $this->setTo($to);
        $this->setFrom($from);
        $this->setMessage($message, $senderName, $signature);
    }

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
     * @param bool $asHtml
     * @return string
     */
    public function getMessage($asHtml = false)
    {
        $message = $this->message;
        if ($asHtml) {
            $message = nl2br($message);
        }
        return $message;
    }

    /**
     * @param $message
     * @param $senderName
     */
    public function setMessage($message, $senderName, $signature)
    {
        $this->message = join("\n", $this->message);
        $this->message = str_replace('%SENDER_NAME%', $senderName, $this->message);
        $this->message = str_replace('%MESSAGE%', $message, $this->message);
        $this->message = str_replace('%SIGNATURE%', $signature, $this->message);
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return \mail\EmailAddress
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param EmailAddress $to
     */
    public function setTo(EmailAddress $to)
    {
        $this->to = $to;
    }

    /**
     * @return EmailAddress
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param EmailAddress $from
     */
    public function setFrom(EmailAddress $from)
    {
        $this->from = $from;
    }
}