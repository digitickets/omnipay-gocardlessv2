<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method SubscriptionResponse send()
 */
class CancelSubscriptionRequest extends AbstractRequest
{
    public function getData()
    {
        return [];
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
        $response = $this->gocardless->subscriptions()->cancel($this->getSubscriptionId());

        return $this->response = new SubscriptionResponse($this, $response);
    }
}
