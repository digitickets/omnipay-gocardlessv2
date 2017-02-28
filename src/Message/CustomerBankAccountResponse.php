<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\CustomerBankAccount;

/**
 * CustomerResponse
 */
class CustomerBankAccountResponse extends AbstractResponse
{
    /**
     * @return CustomerBankAccount|null
     */
    public function getCustomerBankAccountData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }
}
