<?php


namespace Omnipay\GoCardlessV2;


class ProGateway extends AbstractGateway
{

    /**
     * Only to be used with approved payment pages
     *
     * @param array $parameters
     * @return Message\CustomerResponse
     */
    public function createCustomer(array $parameters = array())
    {
        return $this->createRequest(Message\CreateCustomerRequest::class, $parameters);
    }
    /**
     * Only to be used with approved payment pages
     *
     * @param array $parameters
     * @return Message\CustomerBankAccountResponse
     */
    public function createCustomerBankAccount(array $parameters = array())
    {
        return $this->createRequest(Message\CreateCustomerBankAccountRequest::class, $parameters);
    }

    /**
     * For use with the JS Flow process
     *
     * @param array $parameters
     * @return Message\CustomerBankAccountResponse
     */
    public function createCustomerBankAccountFromToken(array $parameters = array())
    {
        return $this->createRequest(Message\CreateCustomerBankAccountRequestFromToken::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\MandateResponse
     */
    public function createMandate(array $parameters = array())
    {
        return $this->createRequest(Message\CreateMandateRequest::class, $parameters);
    }

}
