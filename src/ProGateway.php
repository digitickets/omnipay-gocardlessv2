<?php

namespace Omnipay\GoCardlessV2;

use Omnipay\GoCardlessV2\Message\AbstractRequest;

class ProGateway extends AbstractGateway
{
    /**
     * Only to be used with approved payment pages
     *
     * @param array $parameters
     *
     * @return Message\CustomerResponse|AbstractRequest|Message\AbstractRequest|ProGateway
     */
    public function createCustomer(array $parameters = [])
    {
        return $this->createRequest(Message\CreateCustomerRequest::class, $parameters);
    }

    /**
     * Only to be used with approved payment pages
     *
     * @param array $parameters
     *
     * @return Message\CustomerBankAccountResponse|Message\AbstractRequest|ProGateway
     */
    public function createCustomerBankAccount(array $parameters = [])
    {
        return $this->createRequest(Message\CreateCustomerBankAccountRequest::class, $parameters);
    }

    /**
     * For use with the JS Flow process
     *
     * @param array $parameters
     *
     * @return Message\CustomerBankAccountResponse|Message\AbstractRequest|ProGateway
     */
    public function createCustomerBankAccountFromToken(array $parameters = [])
    {
        return $this->createRequest(Message\CreateCustomerBankAccountRequestFromToken::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\MandateResponse|Message\AbstractRequest|ProGateway
     */
    public function createMandate(array $parameters = [])
    {
        return $this->createRequest(Message\CreateMandateRequest::class, $parameters);
    }
}
