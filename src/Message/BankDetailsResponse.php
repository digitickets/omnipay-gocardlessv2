<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\BankDetailsLookup;

class BankDetailsResponse extends AbstractResponse
{
    /**
     * @return BankDetailsLookup|null
     */
    public function getBankDetailsData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }
}
