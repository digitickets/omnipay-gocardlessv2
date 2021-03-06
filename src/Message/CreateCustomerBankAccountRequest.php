<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method BankAccountResponse send()
 */
class CreateCustomerBankAccountRequest extends AbstractRequest
{
    public function getData()
    {
        $response = $this->getCustomerBankAccountData();
        $response['links']['customer'] = $this->getCustomerReference();
// Remove null values
        foreach($response as $key=>$value){
            if(empty($value)){
                unset($response[$key]);
            }
        }

        return ['params' => $response];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return BankAccountResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customerBankAccounts()->create($data);

        return $this->response = new BankAccountResponse($this, $response);
    }
}
