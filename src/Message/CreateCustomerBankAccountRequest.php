<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method BankAccountResponse send()
 */
class CreateCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        $response = [
            'account_holder_name' => $this->getAccountHolderName(),
            'account_number' => $this->getAccountNumber(),
            'bank_code' => $this->getBankCode(),
            'branch_code' => $this->getBankBranchCode(),
            'country_code' => $this->getBankCountryCode(),
            'currency' => $this->getCurrency(),
            'iban' => $this->getIban(),
            'metadata' => $this->getBankAccountMetaData()];
        $response['links']['customer'] = $this->getCustomerReference();

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
