<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\Refund;

class RefundResponse extends AbstractResponse
{
    /**
     * @return Refund|null
     */
    public function getRefundData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }
}
