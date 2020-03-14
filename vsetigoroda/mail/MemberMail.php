<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 30.04.2018
 * Time: 21:21
 */

namespace vsetigoroda\mail;

use mail\Email;
use mail\EmailAddress;
use mail\Mail;
use mail\WrongEmailAddressException;
use vsetigoroda\Config;

class MemberMail extends Mail
{
    /**
     * @var string
     */
    private $signature;
    /**
     * Member constructor.
     * @param Config $config
     * @throws WrongEmailAddressException
     */
    public function __construct(Config $config)
    {
        $this->setFrom(new EmailAddress(new Email($config->sysmail),$config->from));
        $this->signature = $config->signature;
    }

    /**
     * @return string
     * @throws MemberMailException
     */
    public function getSubject()
    {
        if (!strlen(parent::getSubject())) {
            throw new MemberMailException("Mail subject is empty");
        }
        return parent::getSubject();
    }
}