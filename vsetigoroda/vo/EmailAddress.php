<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 20:30
 */

namespace vsetigoroda\vo;

use mail\Email;

class EmailAddress
{
    /**
     * @var Email
     */
    private $email;
    /**
     * @var string
     */
    private $name;

    /**
     * EmailAddress constructor.
     * @param Email|string $email
     * @param string $name
     * @throws \Exception
     */
    public function __construct($email, $name = '')
    {
        if (!$email instanceof Email) {
            $email = new Email($email);
        }
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}