<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\CustomerBankAccount;

class BankAccountResponse extends AbstractResponse
{
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
}
