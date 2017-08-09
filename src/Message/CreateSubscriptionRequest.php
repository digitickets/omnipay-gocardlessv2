<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * Please note a conflict in terminology - omnipay drivers vs GC flips the use of interval to be the quantity rather than the unit
 *      (Interval vs Interval Unit). setInterval is the time period, not a number. IntervalCount is the number of intervals between each payment.
 *
 *
 * @method SubscriptionResponse send()
 */
class CreateSubscriptionRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency', 'subscription_interval_unit', 'mandateReference');

        $data = [
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'day_of_month' => $this->getSubscriptionDayOfMonth(),
            'interval' => $this->getIntervalCount(),
            'interval_unit' => $this->getInterval(),
            'metadata' => $this->getSubscriptionMetaData(),
            'month' => $this->getSubscriptionMonth(),
            'name' => $this->getPaymentDescription(),
            'payment_reference' => $this->getStatementDescriptor(),
            'start_date' => $this->getPaymentDate(),
            'links' => ['mandate' => $this->getMandateReference()],
        ];
        if ($this->getSubscriptionCount()) {
            $data['count'] = $this->getSubscriptionCount();
        } elseif ($this->getSubscriptionEndDate()) {
            $data['end_date'] = $this->getSubscriptionEndDate();
        }

        // Remove null values
        $data = array_filter(
            $data,
            function ($value) {
                return !is_null($value);
            }
        );

        return ['params' => $data];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return SubscriptionResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->subscriptions()->create($data);

        return $this->response = new SubscriptionResponse($this, $response);
    }
}
