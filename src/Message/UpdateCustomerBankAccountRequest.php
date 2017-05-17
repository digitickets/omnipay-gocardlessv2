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
            'customerBankAccountData' => ['params' => [
                'account_holder_name' => $this->getAccountHolderName(),
                'account_number' => $this->getAccountNumber(),
                'bank_code' => $this->getBankCode(),
                'branch_code' => $this->getBankBranchCode(),
                'country_code' => $this->getBankCountryCode(),
                'currency' => $this->getCurrency(),
                'iban' => $this->getIban(),
                'metadata' => $this->getBankAccountMetaData()]],
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
