<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 21:38
 */

namespace vsetigoroda\billing\mails\tech;


use vsetigoroda\AbstractMail;
use vsetigoroda\billing\AbstractTariff;
use vsetigoroda\Member;
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
     * MemberChangedTariffMail constructor.
     * @param Member $member
     * @param AbstractTariff $oldTariff
     * @param AbstractTariff $newTariff
     */
    public function __construct(Member $member, AbstractTariff $oldTariff, AbstractTariff $newTariff)
    {
        $this->member = $member;
        $this->oldTariff = $oldTariff;
        $this->newTariff = $newTariff;
    }

    /**
     * @return void|EmailAddress
     */
    public function getTo()
    {
        // TODO: Implement getTo() method.
    }

    /**
     * @return void|EmailAddress
     */
    public function getFrom()
    {
        // TODO: Implement getFrom() method.
    }

    /**
     * @return string|void
     */
    public function getSubject()
    {
        return 'Пользователь сменил тариф';
    }

    /**
     * @param bool $asHtml
     * @return string|void
     */
    public function getMessage($asHtml)
    {
        $companyId = $this->member->getCompany() ? $this->member->getCompany()->getId() : 0;
        $companyUrl = "http://$_SERVER[HTTP_HOST]/com_str.php?id=$companyId";
        $memberName = $this->member->getName();
        $oldTariffTitle = $this->oldTariff->getTitle();
        $newTariffTitle = $this->newTariff->getTitle();

        $message = [
            ("Пользователь $memberName " . ($companyId ? "($companyUrl)" : '')),
            "сменил тариф $oldTariffTitle на $newTariffTitle"
        ];
        $message = implode("\n", $message);
        return $message;
    }

}