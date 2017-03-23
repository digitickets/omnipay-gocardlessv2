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
     * @return Message\CreateCustomerRequest|AbstractRequest|Message\AbstractRequest|ProGateway
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
     * @return Message\CreateCustomerBankAccountRequest|Message\AbstractRequest|ProGateway
     */
    public function createBankAccount(array $parameters = [])
    {
        return $this->createRequest(Message\CreateCustomerBankAccountRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\CreateMandateRequest|Message\AbstractRequest|ProGateway
     */
    public function createMandate(array $parameters = [])
    {
        return $this->createRequest(Message\CreateMandateRequest::class, $parameters);
    }
}
