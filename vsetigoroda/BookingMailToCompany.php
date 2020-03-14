<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 02.04.2018
 * Time: 22:57
 */

namespace vsetigoroda;

use Exception;
use vsetigoroda\services\IMailer;
use mail\EmailAddress;

class BookingMailToCompany extends AbstractMail
{
    /**
     * @var string
     */
    private $subject = 'Заказ товара';
    /**
     * @var array
     */
    private $message = [
        'Вам поступила заявка на товар на сайте vseti-goroda.ru.',
        'Фамилия: %firstName%',
        'Имя: %lastName%',
        'Отчество: %middleName%',
        'Телефон: %phone%',
        'Адрес: %address%',
        'Наименование товара: %productName%',
        'Цена товара: %productPrice%',
        '',
        'Просмотреть завявки Вы можете в Вашем личном кабинете:',
        'http://vseti-goroda.ru/member.php',
        '',
        '%signature%'
    ];
    /**
     * @var EmailAddress
     */
    private $from;
    /**
     * @var Booking
     */
    private $booking;
    /**
     * @var Company
     */
    private $company;
    /**
     * @var string
     */
    private $signature;

    /**
     * BookingMailToCompany constructor
     * @param $from
     * @param Booking $booking
     * @param Company $company
     * @param $signature
     * @throws Exception
     */
    public function __construct($from, Booking $booking, Company $company, $signature)
    {
        if (!$from || !$booking || !$company || !$signature) {
            throw new Exception('Не все обязательные ппараметры заданы.');
        }
        $this->setFrom($from);
        $this->setBooking($booking);
        $this->setCompany($company);
        $this->setSignature($signature);
    }

    /**
     * @return \mail\EmailAddress
     * @throws Exception
     */
    public function getTo()
    {
        return new EmailAddress(
            $this->getCompany()->getOwner()->getEmail(),
            $this->getCompany()->getName()
        );
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
     * @param bool $asHtml
     * @return string
     */
    public function getMessage($asHtml = false)
    {
        $message = $this->message;
        $message = join("\n", $message);
        $message = strtr($message, [
            '%firstName%' => $this->getBooking()->getFirstName(),
            '%lastName%' => $this->getBooking()->getLastName(),
            '%middleName%' => $this->getBooking()->getMiddleName(),
            '%phone%' => $this->getBooking()->getPhone(),
            '%address%' => $this->getBooking()->getAddress(),
            '%productName%' => $this->getBooking()->getProductName(),
            '%productPrice%' => $this->getBooking()->getProductPrice(),
            '%signature%' => $this->getSignature(),
        ]);
        if ($asHtml) {
            $message = nl2br($message);
        }
        return $message;
    }

    /**
     * @param array $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return EmailAddress
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \mail\EmailAddress $from
     */
    public function setFrom(EmailAddress $from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param mixed $booking
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }


}