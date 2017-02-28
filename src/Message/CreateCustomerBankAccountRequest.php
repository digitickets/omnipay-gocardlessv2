<?php

namespace Omnipay\GoCardlessV2\Message;

class CreateCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        $response = $this->getCustomerBankAccountData();
        $response['links']['customer'] = $this->getCustomerId();

        return ['params' => $response];
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
        $response = $this->gocardless->customerBankAccounts()->create($data);

        return $this->response = new CustomerBankAccountResponse($this, $response);
    }
}
