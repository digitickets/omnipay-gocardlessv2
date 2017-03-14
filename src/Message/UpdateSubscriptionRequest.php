<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method SubscriptionResponse send()
 */
class UpdateSubscriptionRequest extends AbstractRequest
{
    public function getData()
    {
        return [
            'subscriptionData' => ['params' => ['name' => $this->getPaymentDescription(), 'metadata' => $this->getSubscriptionMetaData()]],
            'subscriptionId' => $this->getSubscriptionId(),
        ];
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
        $response = $this->gocardless->subscriptions()->update($data['subscriptionId'], $data['subscriptionData']);

        return $this->response = new SubscriptionResponse($this, $response);
    }
}
