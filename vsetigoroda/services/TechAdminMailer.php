<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 15.04.2018
 * Time: 0:50
 */

namespace vsetigoroda\services;


use Exception;
use phpmailerException;
use vsetigoroda\Config;
use mail\Email;
use mail\EmailAddress;

class TechAdminMailer extends Mailer implements ITechAdminMailer
{
    /**
     * TechAdminMailer constructor.
     * @param Config $config
     * @throws phpmailerException
     * @throws Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct();
        $this->setFrom(new EmailAddress(
            new Email($config->sysmail),
            $config->from
        ));

        $technicalAdminEmails = explode(',', $config->tech_emails);
        
        foreach ($technicalAdminEmails as $technicalAdminEmail) {
            $email = new EmailAddress($technicalAdminEmail);
            $this->addTo($email);
        }
    }
}