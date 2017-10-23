<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\CustomerBankAccount;

class BankAccountResponse extends AbstractResponse
{
    /**
     * @var CustomerBankAccount|null
     */
    protected $data;

    /**
     * @return CustomerBankAccount|null
     */
    public function getBankAccountData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }

    public function getBankAccountReference()
    {
        return $this->data->id;
    }

    public function getBankName()
    {
        return $this->data->bank_name;
    }

    public function getBankAccountHolder()
    {
        return $this->data->account_holder_name;
    }

    public function getBankAccountNumberEnding()
    {
        return $this->data->account_number_ending;
    }
}
