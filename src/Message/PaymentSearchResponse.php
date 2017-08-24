<?php


namespace Omnipay\GoCardlessV2\Message;


class PaymentSearchResponse extends AbstractSearchResponse
{
    /**
     * @return PaymentResponse
     */
    public function current()
    {
        return new PaymentResponse($this->request, parent::current());
    }

}
