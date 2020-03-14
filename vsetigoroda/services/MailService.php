<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 02.04.2018
 * Time: 14:22
 */

namespace vsetigoroda\services;

use phpmailerException;
use vsetigoroda\billing\AbstractTariff;
use vsetigoroda\billing\DailyPaymentReport;
use vsetigoroda\billing\DailyPaymentReportMailToTechAdmin;
use vsetigoroda\billing\mails\MemberChangedTariffMail as MemberMail;
use vsetigoroda\billing\mails\tech\MemberChangedTariffMail;
use vsetigoroda\Booking;
use vsetigoroda\BookingMailToCompany;
use vsetigoroda\Company;
use vsetigoroda\Config;
use vsetigoroda\ConfigMysqliRepository;
use vsetigoroda\Member;
use vsetigoroda\MemberMysqliRepository;
use vsetigoroda\MailToMember;
use mail\Email;
use mail\EmailAddress;
use vsetigoroda\MemberNotFoundException;
use Exception;

/**
 * Class MailService
 * @package vsetigoroda\services
 */
class MailService
{
    /**
     * @param Booking $booking
     * @param Company $company
     * @return bool
     * @throws Exception
     */
    public static function sendBookingMailToCompany(Booking $booking, Company $company)
    {
        $config = Config::getInstance(new ConfigMysqliRepository());
        return (new BookingMailToCompany(
            new EmailAddress($config->sysmail, $config->from),
            $booking, $company, $config->signature
        ))->send(new Mailer);
    }

    /**
     * @param int $fromUserId
     * @param int $toUserId
     * @param string $message
     * @return bool
     * @throws Exception
     * @throws MemberNotFoundException
     */
    public static function sendMailToMember($fromUserId, $toUserId, $message)
    {
        $config = Config::getInstance(new ConfigMysqliRepository());
        $memberRepository = new MemberMysqliRepository();
        $sender = $memberRepository->getMemberById($fromUserId);
        $recipient = $memberRepository->getMemberById($toUserId);
        return (new MailToMember(
            new EmailAddress($recipient->getEmail(), $recipient->getName()),
            new EmailAddress($config->sysmail, $config->from),
            $sender->getName(), $message, $config->signature
        ))->send(new Mailer);
    }

    /**
     * @param DailyPaymentReport $report
     * @return bool
     * @throws phpmailerException
     */
    public static function sendDailyPaymentMailReport(DailyPaymentReport $report)
    {
        $config = Config::getInstance(new ConfigMysqliRepository());
        $mailer = new TechAdminMailer($config);
        $mailer->addTo(new EmailAddress(new Email($config->sysmail)));
        $mailReport = new DailyPaymentReportMailToTechAdmin($report);
        return $mailReport->sendToTechAdmin($mailer);
    }

    /**
     * @param Member $member
     * @param AbstractTariff $oldTariff
     * @param AbstractTariff $newTariff
     * @return mixed
     * @throws phpmailerException
     */
    public static function memberChangedTariffAlert(Member $member, AbstractTariff $oldTariff, AbstractTariff $newTariff)
    {
        $config = Config::getInstance(new ConfigMysqliRepository());
        $techMailer = new TechAdminMailer($config);
        $techMailer->addTo(new EmailAddress(new Email($config->sysmail)));

        $mailAlert = new MemberChangedTariffMail($member, $oldTariff, $newTariff);
        $mailAlert->sendToTechAdmin($techMailer);

        $mailer = new Mailer;
        $mail = new MemberMail($member,$oldTariff, $newTariff,
            new EmailAddress($config->sysmail, $config->from), $config->signature);
        $mail->send($mailer);
    }

}