<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method RefundResponse send()
 */
class FindRefundRequest extends AbstractRequest
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
     * @return RefundResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->refunds()->get($this->getTransactionReference());

        return $this->response = new RefundResponse($this, $response);
    }
}
