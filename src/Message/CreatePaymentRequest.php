<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method PaymentResponse send()
 */
class CreatePaymentRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency', 'mandateReference');

        // Remove null values
        $data = array_filter(
            [
                'amount' => $this->getAmountInteger(),
                'description' => $this->getPaymentDescription(),
                'app_fee' => $this->getServiceFeeAmount(),
                'metadata' => $this->getPaymentMetaData(),
                'charge_date' => $this->getPaymentDate(),
                'currency' => $this->getCurrency(),
                'reference' => $this->getReference(),
                'links' => ['mandate' => $this->getMandateReference()],
            ],
            function ($value) {
                return !empty($value);
            }
        );

        return ['params' => $data];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return PaymentResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->payments()->create($data);

        return $this->response = new PaymentResponse($this, $response);
    }
}
