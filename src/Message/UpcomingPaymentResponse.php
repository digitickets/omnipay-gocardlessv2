<?php

namespace Omnipay\GoCardlessV2\Message;


class UpcomingPaymentResponse
{
    /**
     * @var bool|\DateTime
     */
    private $chargeDate;
    /**
     * @var int
     */
    private $amount;

    /**
     * @param bool|\DateTime|null $chargeDate
     * @param int|null $amount
     */
    public function __construct($chargeDate = null, $amount = null)
    {
        if($chargeDate){
            $this->setChargeDate($chargeDate);
        }
        if($amount){
            $this->setAmount($amount);
        }
    }

    /**
     * @return bool|\DateTime
     */
    public function getChargeDate()
    {
        return $this->chargeDate;
    }

    /**
     * @param bool|\DateTime $chargeDate
     */
    public function setChargeDate($chargeDate)
    {
        $this->chargeDate = $chargeDate;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return round($this->amount / 100, 2);
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }


}
