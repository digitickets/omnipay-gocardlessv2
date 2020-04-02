<?php

namespace Omnipay\GoCardlessV2\Message;

class FindPaymentsBySubscriptionRequest extends AbstractSearchRequest
{
    public function getData()
    {
        $this->validate("subscriptionId");
        $data = [];
        $paramValue = $this->getSubscriptionId();
        if (!empty($paramValue)) {
            $data['subscription'] = $paramValue;
        }

        return $data;
    }

    /**
     * @param array $data
     * @return PaymentSearchResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->payments()->all(["params" => $data]);

        return $this->response = new PaymentSearchResponse($this, $response);
    }
}
