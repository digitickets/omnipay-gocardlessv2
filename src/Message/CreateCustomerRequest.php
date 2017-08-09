<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method CustomerResponse send()
 */
class CreateCustomerRequest extends AbstractRequest
{
    public function getData()
    {
        $creditCard = $this->getCard();

        return [
            'params' => [
                'email' => $creditCard->getEmail(),
                'given_name' => $creditCard->getFirstName(),
                'family_name' => $creditCard->getLastName(),
                'country_code' => $creditCard->getCountry(),
                'metadata' => $this->getCustomerMetaData(),
                'address_line1' => $creditCard->getAddress1(),
                'address_line2' => $creditCard->getAddress2(),
                'city' => $creditCard->getCity(),
                'company_name' => $creditCard->getCompany(),
                'postal_code' => $creditCard->getPostcode(),
                'region' => $creditCard->getState(),
                'swedish_identity_number' => $this->getSwedishIdentityNumber(),
            ],
        ];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return CustomerResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->customers()->create($data);

        return $this->response = new CustomerResponse($this, $response);
    }
}
