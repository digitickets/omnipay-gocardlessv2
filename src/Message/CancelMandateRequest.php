<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method MAndateResponse send()
 */
class CancelMandateRequest extends AbstractRequest
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
     * @return MandateResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->mandates()->cancel($this->getMandateId());

        return $this->response = new MandateResponse($this, $response);
    }

}
