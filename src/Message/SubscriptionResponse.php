<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * Subscription Response
 */
class SubscriptionResponse extends Response
{
    public function getSubscriptionData()
    {
        if (isset($this->data->subscription)) {
            return $this->data->subscription;
        }

        return null;
    }
}
