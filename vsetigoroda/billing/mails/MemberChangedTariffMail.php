<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 21:55
 */

namespace vsetigoroda\billing\mails;


use vsetigoroda\AbstractMail;
use vsetigoroda\billing\AbstractTariff;
use vsetigoroda\Member;
use mail\Email;
use mail\EmailAddress;

class MemberChangedTariffMail extends AbstractMail
{
    /**
     * @var Member
     */
    private $member;
    /**
     * @var AbstractTariff
     */
    private $oldTariff;
    /**
     * @var AbstractTariff
     */
    private $newTariff;
    /**
     * @var EmailAddress
     */
    private $from;
    /**
     * @var string
     */
    private $signature;

    /**
     * MemberChangedTariffMail constructor.
     * @param Member $member
     * @param AbstractTariff $oldTariff
     * @param AbstractTariff $newTariff
     * @param EmailAddress $from
     * @param EmailAddress $to
     * @param string $signature
     */
    public function __construct(Member $member, AbstractTariff $oldTariff, AbstractTariff $newTariff,
                                EmailAddress $from, $signature)
    {
        $this->member = $member;
        $this->oldTariff = $oldTariff;
        $this->newTariff = $newTariff;
        $this->from = $from;
        $this->signature = $signature;
    }


    public function getTo()
    {
        $to = new EmailAddress(new Email($this->member->getEmail()), $this->member->getName());
        return $to;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getSubject()
    {
        return 'Вы сменили тарифный план';
    }

    public function getMessage($asHtml)
    {
        $oldName = $this->oldTariff->getTitle();
        $newName = $this->newTariff->getTitle();
        $perDay = $this->newTariff->getDayPrice()->getCost();

        $message = [
            "Уважаемый пользователь!",
            "Вы сменили Ваш тарифный план $oldName на $newName.",
            "",
            "Стоимость обслуживания будет составлять $perDay руб. в день.",
            "",
            $this->signature,
        ];
        return join("\n", $message);
    }

}