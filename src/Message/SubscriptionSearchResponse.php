<?php


namespace Omnipay\GoCardlessV2\Message;


class SubscriptionSearchResponse extends AbstractSearchResponse
{
    /**
     * @return SubscriptionResponse
     */
    public function current()
    {
        return new SubscriptionResponse($this->request, parent::current());
    }
}
