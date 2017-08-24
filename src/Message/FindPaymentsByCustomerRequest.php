<?php


namespace Omnipay\GoCardlessV2\Message;


class FindPaymentsByCustomerRequest extends AbstractSearchRequest
{

    public function getData()
    {
        $this->validate("customerReference");
        $data = [];
        $paramValue = $this->getCustomerReference();
        if (!empty($paramValue)) {
            $data['customer'] = $paramValue;
        }

        return $data;
    }

    /**
     * @param array $data
     * @return PaymentSearchResponse
     */
    public function sendData($data)
    {

        $response = $this->gocardless->payments()->all(["params" => $data]);

        return $this->response = new PaymentSearchResponse($this, $response);
    }
}
