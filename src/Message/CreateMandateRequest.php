<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method MandateResponse send()
 */
class CreateMandateRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getMandateData();
        $data['links']['customer_bank_account'] = $this->getBankAccountReference();
        if ($this->getCreditorId()) {
            $data['links']['creditor'] = $this->getCreditorId();
        }

        return ['params' => $data];
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
        $response = $this->gocardless->mandates()->create($data);

        return $this->response = new MandateResponse($this, $response);
    }
}
