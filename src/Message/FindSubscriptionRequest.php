<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method SubscriptionResponse send()
 */
class FindSubscriptionRequest extends AbstractRequest
{
    public function getData()
    {
        return [];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data - completely ignored, included for consistency
     *
     * @return SubscriptionResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->subscriptions()->get($this->getSubscriptionId());

        return $this->response = new SubscriptionResponse($this, $response);
    }
}
