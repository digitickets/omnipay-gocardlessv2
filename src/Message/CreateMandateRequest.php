<?php
namespace Omnipay\GoCardlessV2\Message;

class CreateMandateRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getMandateData();
        $data['links']['customer_bank_account'] = $this->getCustomerBankAccountId();
        $data['links']['creditor'] = $this->getCreditorId();

        return array("params" => $data);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return MandateResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->mandates()->create($data);

        return $this->response = new MandateResponse($this, $response);
    }
}
