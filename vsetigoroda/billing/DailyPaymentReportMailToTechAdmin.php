<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 15:51
 */

namespace vsetigoroda\billing;

use Exception;
use vsetigoroda\AbstractMail;
use vsetigoroda\billing\DailyPaymentReport;
use mail\EmailAddress;

class DailyPaymentReportMailToTechAdmin extends AbstractMail
{
    const DEFAULT_SUBJECT = 'Техническое сообщение';
    /**
     * @var array
     */
    private $message = [
        'Отчет по ежедневному списанию за %%datetime%%.',
        'Всего списано %%totalPaid%% руб..',
        '',
        '%%report%%',
    ];
    /**
     * @var string
     */
    private $subject = 'Отчет по ежедневному списанию за %%datetime%%';
    /**
     * @var DailyPaymentReport
     */
    private $report;

    /**
     * DailyPaymentReportMailToTechAdmin constructor.
     * @param DailyPaymentReport $report
     * @throws Exception
     */
    public function __construct(DailyPaymentReport $report)
    {
        if (!$report) {
            throw new Exception('Заданы не все обязательные параметры');
        }
        $this->report = $report;
    }

    /**
     * @param bool $asHtml
     * @return string
     */
    public function getMessage($asHtml = false)
    {
//        $report = [
//            implode("\t\t\t", [
//                'ИД клиента',
//                'Имя клиента',
//                'Тарифный план',
//                'Стоимость',
//                'Баланс до',
//                'Баланс после',
//                'Результат операции',
//
//            ]),
//            '',
//        ];
        foreach ($this->report->getRows() as $row) {
            $report[] = implode(' ', [
                ($row->getMemberName() . ':'),
                $row->getTariffName(),
                ($row->getBalanceBefore()->getCost() . 'руб. - '),
                ($row->getTariffPerDay()->getCost() . 'руб. => '),
                ($row->getBalanceAfter()->getCost() . 'руб. '),
                ('(' . $row->getOperationResult() . ')'),
            ]);
        }

        $message = implode("\n", $this->message);
        $message = str_replace('%%datetime%%', $this->report->getDatetime()->toString(), $message);
        $message = str_replace('%%totalPaid%%', $this->report->getTotalPaid()->getCost(), $message);
        $message = str_replace('%%report%%', implode("\n", $report), $message);

        if ($asHtml) {
            $message = nl2br($message);
        }
        return $message;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        $subject = $this->subject;
        $subject = str_replace('%%datetime%%', $this->report->getDatetime()->toString(), $subject);
        return $subject;
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
     * @return \mail\EmailAddress
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
}