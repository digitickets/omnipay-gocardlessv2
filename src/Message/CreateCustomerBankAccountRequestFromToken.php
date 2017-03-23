<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method BankAccountResponse send()
 */
class CreateCustomerBankAccountRequestFromToken extends AbstractRequest
{
    public function getData()
    {
        $response['links']['customer'] = $this->getCustomerReference();
        $response['links']['customer_bank_account_token'] = $this->getCustomerBankAccountToken();

        return ['params' => $response];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return BankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->create($data);

        return $this->response = new BankAccountResponse($this, $response);
    }
}
