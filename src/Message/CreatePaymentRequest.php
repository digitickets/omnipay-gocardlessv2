<?php
namespace Omnipay\GoCardlessV2\Message;

class CreatePaymentRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency', 'mandateId');

        $data = array(
            'amount' => $this->getAmountInteger(),
            'description' => $this->getPaymentDescription(),
            'app_fee' => $this->getServiceFeeAmount(),
            'metadata' => $this->getPaymentMetaData(),
            'charge_date' => $this->getPaymentDate(),
            'currency' => $this->getCurrency(),
            'reference' => $this->getReference(),
            'links' => array('mandate' => $this->getMandateId()),
        );

        // Remove null values
        $data = array_filter(
            $data,
            function ($value) {
                return !is_null($value);
            }
        );

        return array("params" => $data);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return PaymentResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->payments()->create($data);

        return $this->response = new PaymentResponse($this, $response);
    }

}
