<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method BankAccountResponse send()
 */
class ValidateBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        $response = $this->getCustomerBankAccountData();

        return ['params' => $response];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return BankDetailsResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->bankDetailsLookups()->create($data);

        return $this->response = new BankDetailsResponse($this, $response);
    }
}
