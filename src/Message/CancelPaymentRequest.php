<?php
namespace Omnipay\GoCardlessV2\Message;

class CancelPaymentRequest extends AbstractRequest
{
    public function getData()
    {
        return array();
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return PaymentResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->payments()->cancel($this->getPaymentId());

        return $this->response = new PaymentResponse($this, $response);
    }
}
