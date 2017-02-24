<?php
namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Authorize Request
 *
 * @method CustomerResponse send()
 */
class UpdateMandateRequest extends AbstractRequest
{
    public function getData()
    {
        return array(
            'mandateData' => $this->getMandateData(),
            'mandateId' => $this->getMandateId(),
        );
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return MandateResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->mandates()->update($data['mandateId'], $data['mandateData']);

        return $this->response = new MandateResponse($this, $response);
    }
}
