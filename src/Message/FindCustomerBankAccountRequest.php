<?php
namespace Omnipay\GoCardlessV2\Message;

/**
 * Find Customer Request
 *
 * @method CustomerResponse send()
 */
class FindCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        return array();
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data completely ignored - there for consistency
     * @return CustomerBankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->get($this->getCustomerBankAccountID());

        return $this->response = new CustomerBankAccountResponse($this, $response);
    }
}
