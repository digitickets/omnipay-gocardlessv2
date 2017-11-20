<?php

namespace Omnipay\GoCardlessV2\Message;

use DateTime;
use GoCardlessPro\Resources\Subscription;

class SubscriptionResponse extends AbstractResponse
{
    const PENDING_CUSTOMER_APPROVAL = 'pending_customer_approval';
    const CUSTOMER_APPROVAL_DENIED = 'customer_approval_denied';
    const ACTIVE = 'active';
    const FINISHED = 'finished';
    const CANCELLED = 'cancelled';

    /**
     * @var Subscription
     */
    protected $data;

    /**
     * @return Subscription|null
     */
    public function getSubscriptionData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }

    public function getSubscriptionReference()
    {
        return $this->data->id;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return round($this->data->amount / 100, 2);
    }

    /**
     * @return bool|DateTime
     */
    public function getCreatedAt()
    {
        return $this->getDateTime('created_at');
    }

    public function getCurrency()
    {
        return $this->data->currency;
    }

    public function getStatus()
    {
        return $this->data->status;
    }

    public function getName()
    {
        return $this->data->name;
    }

    /**
     * @return bool|DateTime
     */
    public function getStartDate()
    {
        return $this->getDate('start_date');
    }

    /**
     * @return bool|DateTime
     */
    public function getEndDate()
    {
        return $this->getDate('end_date');
    }

    public function getInterval()
    {
        return $this->data->interval;
    }

    public function getIntervalUnit()
    {
        return $this->data->interval_unit;
    }

    public function getDayOfMonth()
    {
        return $this->data->day_of_month;
    }

    public function getMonth()
    {
        return $this->data->month;
    }

    public function getPaymentReference()
    {
        return $this->data->payment_reference;
    }

    public function getUpcomingPayments()
    {
        $return = [];
        foreach ($this->data->upcoming_payments as $payment) {
            $chargeDate = \DateTime::createFromFormat('!Y-m-d', $payment->charge_date);
            $return[] = new UpcomingPaymentResponse($chargeDate, $payment->amount);
        }

        return $return;
    }

    public function isOutstanding()
    {
        $arr = [self::PENDING_CUSTOMER_APPROVAL, self::ACTIVE];
        if (in_array($this->getStatus(), $arr)) {
            return true;
        } else {
            return false;
        }
    }

    public function getLinkMandate()
    {
        return $this->getLinkField('mandate');
    }
}
