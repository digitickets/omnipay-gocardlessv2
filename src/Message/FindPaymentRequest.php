<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method PaymentResponse send()
 */
class FindPaymentRequest extends AbstractRequest
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
     * @return PaymentResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->payments()->get($this->getPaymentId());

        return $this->response = new PaymentResponse($this, $response);
    }
}
