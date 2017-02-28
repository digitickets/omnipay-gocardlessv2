<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\Payment;

/**
 * Payment Response
 */
class PaymentResponse extends AbstractResponse
{
    /**
     * @return Payment|null
     */
    public function getPaymentData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }
}
