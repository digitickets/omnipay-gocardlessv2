<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method BankAccountResponse send()
 */
class UpdateCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        return [
            'customerBankAccountData' => ['params' => $this->getCustomerBankAccountData()],
            'customerBankAccountId' => $this->getBankAccountReference(),
        ];
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
        $response = $this->gocardless->customerBankAccounts()->update($data['customerBankAccountId'], $data['customerBankAccountData']);

        return $this->response = new BankAccountResponse($this, $response);
    }
}
