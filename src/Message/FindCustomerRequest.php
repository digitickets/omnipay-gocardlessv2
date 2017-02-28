<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * Find Customer Request
 *
 * @method CustomerResponse send()
 */
class FindCustomerRequest extends AbstractRequest
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
     * @return CustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customers()->get($this->getCustomerId());

        return $this->response = new CustomerResponse($this, $response);
    }
}
