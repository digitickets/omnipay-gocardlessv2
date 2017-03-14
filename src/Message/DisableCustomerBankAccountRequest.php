<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method CustomerBankAccountResponse send()
 */
class DisableCustomerBankAccountRequest extends AbstractRequest
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
     * @return CustomerBankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->disable($this->getCustomerBankAccountId());

        return $this->response = new CustomerBankAccountResponse($this, $response);
    }
}
