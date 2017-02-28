<?php
namespace Omnipay\GoCardlessV2\Message;

class UpdateCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {

        return array(
            'customerBankAccountData' => array("params" => $this->getCustomerBankAccountData()),
            'customerBankAccountId' => $this->getCustomerBankAccountId(),
        );
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return CustomerBankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->update($data['customerBankAccountId'], $data['customerBankAccountData']);

        return $this->response = new CustomerBankAccountResponse($this, $response);
    }
}
