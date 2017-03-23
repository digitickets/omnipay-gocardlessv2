<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method MandateResponse send()
 */
class UpdateMandateRequest extends AbstractRequest
{
    public function getData()
    {
        return [
            'mandateData' => ['params' => $this->getMandateData()],
            'mandateId' => $this->getMandateReference(),
        ];
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
        $response = $this->gocardless->mandates()->update($data['mandateId'], $data['mandateData']);

        return $this->response = new MandateResponse($this, $response);
    }
}
