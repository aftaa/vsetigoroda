<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 14.04.2018
 * Time: 23:26
 */

namespace vsetigoroda\services\cron;

use Exception;
use vsetigoroda\billing\DailyPaymentHistory;
use vsetigoroda\billing\DailyPaymentHistoryMysqliRepository;
use vsetigoroda\billing\DailyPaymentReport;
use vsetigoroda\billing\DailyPaymentReportMailToTechAdmin;
use vsetigoroda\billing\DailyPaymentReportRow;
use vsetigoroda\billing\ITariffRepository;
use vsetigoroda\billing\Payment;
use vsetigoroda\billing\PaymentAlreadyPaidException;
use vsetigoroda\billing\PaymentException;
use vsetigoroda\IMemberRepository;
use vsetigoroda\Member;
use vsetigoroda\MemberMysqliRepository;
use vsetigoroda\services\MailService;
use vsetigoroda\services\MysqliDbService;
use vsetigoroda\vo\currency\Money;

class DailyPaymentService
{
    /**
     * @var MemberMysqliRepository
     */
    private $memberRepository;
    /**
     * @var Member[]
     */
    private $members;
    /**
     * @var DailyPaymentReport
     */
    private $report;
    /**
     * DailyPaymentService constructor.
     * @throws Exception
     */
    private $paymentHistoryRepository;

    /**
     * DailyPaymentService constructor.
     * @throws Exception
     */
    private $db;

    public function __construct()
    {
        $this->db = MysqliDbService::getInstance()->getDb();
        $this->paymentHistoryRepository = new DailyPaymentHistoryMysqliRepository;
        $this->memberRepository = new MemberMysqliRepository;
        $this->members = $this->memberRepository->getAll();
        $this->report = new DailyPaymentReport;
    }

    /**
     * @throws Exception
     */
    public function doPayments()
    {
        $this->db->autocommit(false);

        foreach ($this->members as $member) {
            if ($member->getAccount()->isDailyPaymentActivated()) {
                $balanceBefore = clone $member->getAccount()->getBalance();

                try {
                    $payment = $this->doPayment($member, $balanceBefore);
                    $this->makeReportRow($member, $balanceBefore,
                        $payment->getResult(), $payment->getResultName()
                    );
                } catch (PaymentAlreadyPaidException $e) {
                    $this->makeReportRow($member, $balanceBefore,
                        null, $e->getMessage()
                    );
                }
            }
        }
        //MailService::sendDailyPaymentMailReport($this->report);
        $this->db->commit();
    }

    /**
     * @param Member $member
     * @param int $balanceBefore
     * @return Payment
     * @throws Exception
     */
    public function doPayment(Member $member, $balanceBefore)
    {
        try {
            $payment = new Payment($member,$member->getTariff()->getDayPrice());
            if ($member->getAccount()->wasDailyPaymentToday()) {
                $date = $member->getAccount()->getLastPaymentDate()->format('d.m.Y');
                throw new PaymentAlreadyPaidException("Платеж за $date уже списан");
            } else {
                $payment->apply();
                $this->memberRepository->saveMember($member);
                $this->paymentHistoryRepository->addLog($member, new DailyPaymentHistory(
                    $member->getTariff(),
                    $member->getTariff()->getMonthPrice(),
                    $member->getTariff()->getDayPrice(),
                    $this->report->getDatetime(),
                    $balanceBefore,
                    $member->getAccount()->getBalance()
                ));
            }
        } catch (PaymentException $e) {
            // TODO обработка - уведомления о балансе и т.д.
        }
        return $payment;
    }

    /**
     * @param Member $member
     * @param Money $balanceBefore
     * @param int $opCode
     * @param string $opName
     * @throws Exception
     */
    private function makeReportRow(Member $member, Money $balanceBefore, $opCode, $opName)
    {
        $this->report->addRow(new DailyPaymentReportRow(
            $member->getId(),
            $member->getName(),
            $member->getTariff()->getTitle(),
            $member->getTariff()->getDayPrice(),
            $balanceBefore,
            $member->getAccount()->getBalance(),
            $opName,
            $opCode
        ));
    }
}