<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 15:51
 */

namespace vsetigoroda;

use Exception;
use vsetigoroda\billing\DailyPaymentReport;
use vsetigoroda\vo\EmailAddress;

class TechMailToAdmin extends AbstractMail
{
    const DEFAULT_SUBJECT = 'Техническое сообщение';
    /**
     * @var array|string
     */
    private $message;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var EmailAddress[]
     */
    private $to = [];
    /**
     * @var EmailAddress
     */
    private $from;

    /**
     * TechMailToAdmin constructor.
     * @param array $to
     * @param EmailAddress $from
     * @param DailyPaymentReport $report
     * @param $message
     * @param string $subject
     * @throws Exception
     */
    public function __construct(array $to, EmailAddress $from, DailyPaymentReport $report,
                                $message, $subject = self::DEFAULT_SUBJECT)
    {
        if (!$to || !$from || !$message || !$subject) {
            throw new Exception('Заданы не все обязательные параметры');
        }
        $this->setTo($to);
        $this->setFrom($from);
        $this->setSubject($subject);
        $this->setMessage($message);
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
     * @param array|string $message
     */
    public function setMessage($message)
    {
        if (is_array($message)) {
            $this->message = implode("\n", $message);
        } else {
            $this->message = $message;
        }
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
     * @return EmailAddress[]
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param EmailAddress|EmailAddress[] $to
     * @throws Exception
     */
    public function setTo($to)
    {
        if ($to instanceof EmailAddress) {
            $this->to = [$to];
        } elseif (is_array($to)) {
            $this->to = $to;
            foreach ($this->to as $to) {
                if (!$to instanceof EmailAddress) {
                    throw new Exception("$to isn't value object of class EmailAddress");
                }
            }
        }
        throw new Exception("$to isn't value object of class EmailAddress");
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