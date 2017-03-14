<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method CustomerResponse send()
 */
class UpdateCustomerRequest extends AbstractRequest
{
    public function getData()
    {
        return [
            'customerData' => ['params' => $this->getCustomerData()],
            'customerId' => $this->getCustomerId(),
        ];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return CustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customers()->update($data['customerId'], $data['customerData']);

        return $this->response = new CustomerResponse($this, $response);
    }
}
