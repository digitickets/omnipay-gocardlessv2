<?php
namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

class CreateSubscriptionRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency', 'subscriptionIntervalUnit', 'mandateId');

        $data = array(
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'day_of_month' => $this->getSubscriptionDayOfMonth(),
            'interval' => $this->getSubscriptionInterval(),
            'interval_unit' => $this->getSubscriptionIntervalUnit(),
            'metadata' => $this->getSubscriptionMetaData(),
            'month' => $this->getSubscriptionMonth(),
            'name' => $this->getPaymentDescription(),
            'payment_reference' => $this->getReference(),
            'start_date' => $this->getPaymentDate(),
            'links' => array('mandate' => $this->getMandateId()),
        );
        if ($this->getSubscriptionCount()) {
            $data['count'] = $this->getSubscriptionCount();
        } elseif ($this->getSubscriptionEndDate()) {
            $data['end_date'] = $this->getSubscriptionEndDate();
        } else {
            throw new InvalidRequestException("The subscription count or end date should be set.");
        }

        // Remove null values
        $data = array_filter(
            $data,
            function ($value) {
                return !is_null($value);
            }
        );


        return array("params" => $data);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return SubscriptionResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->subscriptions()->create($data);

        return $this->response = new SubscriptionResponse($this, $response);
    }

}
