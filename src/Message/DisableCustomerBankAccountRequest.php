<?php
namespace Omnipay\GoCardlessV2\Message;

/**
 * Find Customer Request
 *
 * @method CustomerResponse send()
 */
class DisableCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        return array();
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return CustomerBankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->disable($this->getCustomerBankAccountID());

        return $this->response = new CustomerBankAccountResponse($this, $response);
    }
}
