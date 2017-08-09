<?php

namespace Omnipay\GoCardlessV2\Message;

use DateTime;
use GoCardlessPro\Resources\Payment;

/**
 * Payment Response
 */
class PaymentResponse extends AbstractResponse
{
    /**
     * @return Payment|null
     */
    public function getPaymentData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }

    public function getPaymentReference()
    {
        return $this->data->id;
    }

    public function getAmount()
    {
        return round($this->data->amount / 100, 2);
    }

    public function getAmountRefunded()
    {
        return round($this->data->amount_refunded / 100, 2);
    }

    /**
     * @return bool|DateTime
     */
    public function getCreatedAt()
    {
        return DateTime::createFromFormat('!Y-m-d?H:i:s.u?', $this->data->created_at);
    }

    /**
     * @return bool|DateTime
     */
    public function getChargeDate()
    {
        return DateTime::createFromFormat('!Y-m-d?H:i:s.u?', $this->data->charge_date);
    }

    public function getCurrency()
    {
        return $this->data->currency;
    }

    public function getDescription()
    {
        return $this->data->currency;
    }

    public function getReference()
    {
        return $this->data->reference;
    }

    public function getStatus()
    {
        return $this->data->status;
    }
}
