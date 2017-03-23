<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method BankAccountResponse send()
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
     * @return BankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->get($this->getBankAccountReference());

        return $this->response = new BankAccountResponse($this, $response);
    }
}
