<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method BankAccountResponse send()
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
     * @return BankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->disable($this->getBankAccountReference());

        return $this->response = new BankAccountResponse($this, $response);
    }
}
