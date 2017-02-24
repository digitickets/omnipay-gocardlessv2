<?php
namespace Omnipay\GoCardlessV2\Message;

/**
 * Find Customer Request
 *
 * @method CustomerResponse send()
 */
class ReinstateMandateRequest extends AbstractRequest
{
    public function getData()
    {
        return array();
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return MandateResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->mandates()->reinstate($this->getMandateId());

        return $this->response = new MandateResponse($this, $response);
    }
}
