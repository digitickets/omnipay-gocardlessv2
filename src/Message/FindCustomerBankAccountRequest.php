<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method CustomerBankAccountResponse send()
 */
class FindCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        return [];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data completely ignored - there for consistency
     *
     * @return CustomerBankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->get($this->getCustomerBankAccountId());

        return $this->response = new CustomerBankAccountResponse($this, $response);
    }
}
