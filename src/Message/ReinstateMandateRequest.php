<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method MandateResponse send()
 */
class ReinstateMandateRequest extends AbstractRequest
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
        $response = $this->gocardless->mandates()->reinstate($this->getMandateReference());

        return $this->response = new MandateResponse($this, $response);
    }
}
