<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method MandateResponse send()
 */
class CancelMandateRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('mandateReference');

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
        $response = $this->gocardless->mandates()->cancel($this->getMandateReference());

        return $this->response = new MandateResponse($this, $response);
    }
}
