<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method MandateResponse send()
 */
class FindMandateRequest extends AbstractRequest
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
     * @return MandateResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->mandates()->get($this->getMandateReference());

        return $this->response = new MandateResponse($this, $response);
    }
}
