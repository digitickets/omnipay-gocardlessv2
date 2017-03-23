<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\Subscription;

class SubscriptionResponse extends AbstractResponse
{
    /**
     * @return Subscription|null
     */
    public function getSubscriptionData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }

    public function getSubscriptionReference()
    {
        return $this->data->id;
    }
}
