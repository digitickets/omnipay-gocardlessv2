<?php
namespace Omnipay\GoCardlessV2\Message;

class CancelSubscriptionRequest extends AbstractRequest
{
    public function getData()
    {
        return array();
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return SubscriptionResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->subscriptions()->cancel($this->getSubscriptionId());

        return $this->response = new SubscriptionResponse($this, $response);
    }
}
