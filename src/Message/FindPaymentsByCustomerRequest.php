<?php


namespace Omnipay\GoCardlessV2\Message;


class FindPaymentsByCustomerRequest extends AbstractSearchRequest
{

    public function getData()
    {
        $data = [];
        $paramValue = $this->getCustomerReference();
        if (!empty($paramValue)) {
            $data['customer'] = $paramValue;
        }

        if(empty($data)){
            throw new \Exception("Parameters must be specified for this search");
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
