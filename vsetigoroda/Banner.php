<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 07.04.2018
 * Time: 1:39
 */

namespace vsetigoroda;

use Exception;
use vsetigoroda\Company;

class Banner
{
    const URL = 'http://vseti-goroda.ru/com_str.php?id=%companyId%';

    /**
     * @var array
     */
    private $HTML = [
        '<a href="%URL%" target="_blank"><img alt="%companyName% vseti-goroda" src="/images/ico/vsetiiii.png" ',
        'width="140" height="40"></a>',
    ];

    /**
     * @var Company
     */
    private $company;

    /**
     * Banner constructor.
     * @param Company $company
     * @throws Exception
     */
    public function __construct(Company $company)
    {
        if (empty($company)) {
            throw new Exception('$company is empty');
        }
        $this->setCompany($company);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $url = self::URL;
        $url = str_replace('%companyId%', $this->getCompany()->getId(), $url);
        $html = $this->HTML;
        $html = implode('', $html);
        $html = str_replace('%companyName%', $this->getCompany()->getName(), $html);
        $html = str_replace('%URL%', $url, $html);
        return $html;
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