<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method PaymentResponse send()
 */
class RetryPaymentRequest extends AbstractRequest
{
    public function getData()
    {
        return [
            'paymentId' => $this->getPaymentId(),
        ];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return PaymentResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->payments()->retry($data['paymentId']);

        return $this->response = new PaymentResponse($this, $response);
    }
}
