<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\Customer;

/**
 * CustomerResponse
 */
class CustomerResponse extends AbstractResponse
{
    /**
     * @return Customer|null
     */
    public function getCustomerData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }

    public function getCustomerReference()
    {
        return $this->data->id;
    }
}
