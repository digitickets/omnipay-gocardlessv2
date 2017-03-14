<?php

namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * @method RefundResponse send()
 */
class CreateRefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        return [
            'params' => [
                'links' => ['payment' => $this->getTransactionReference()],
                'amount' => $this->getAmountInteger(),
                'total_amount_confirmation' => $this->getTotalRefundedAmount(),
                'reference' => $this->getReference(),
                'metadata' => $this->getPaymentMetaData(),
            ],
        ];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $response = $this->gocardless->refunds()->create($data);

        return $this->response = new RefundResponse($this, $response);
    }
}
