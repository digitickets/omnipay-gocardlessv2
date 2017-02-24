<?php
namespace Omnipay\GoCardlessV2\Message;

class CreateCustomerBankAccountRequestFromToken extends AbstractRequest
{
    public function getData()
    {
        $response['links']['customer'] = $this->getCustomerId();
        $response['links']['customer_bank_account_token'] = $this->getCustomerBankAccountToken();
        return $response;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return CustomerBankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->create($data);

        return $this->response = new CustomerBankAccountResponse($this, $response);
    }
}
