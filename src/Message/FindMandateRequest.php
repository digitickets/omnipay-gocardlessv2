<?php
namespace Omnipay\GoCardlessV2\Message;

/**
 * Find Customer Request
 *
 * @method CustomerResponse send()
 */
class FindMandateRequest extends AbstractRequest
{
    public function getData()
    {
        return array();
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data - completely ignored, included for consistency
     * @return MandateResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->mandates()->get($this->getMandateId());

        return $this->response = new MandateResponse($this, $response);
    }
}
