<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method SubscriptionResponse send()
 */
class FindSubscriptionsByCustomerRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate("customerReference");
        $data = [];
        $paramValue = $this->getCustomerReference();
        if (!empty($paramValue)) {
            $data['customer'] = $paramValue;
        }

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param mixed $data - completely ignored, included for consistency
     *
     * @return SubscriptionSearchResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->subscriptions()->all(["params" => $data]);

        return $this->response = new SubscriptionSearchResponse($this, $response);
    }
}
