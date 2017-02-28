<?php
namespace Omnipay\GoCardlessV2\Message;


/**
 * Authorize Request
 */
class CreateCustomerRequest extends AbstractRequest
{
    public function getData()
    {
        return array("params"=>$this->getCustomerData());
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return CustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customers()->create($data);

        return $this->response = new CustomerResponse($this, $response);
    }
}
