<?php

namespace Omnipay\GoCardlessV2;

use Omnipay\GoCardlessV2\Message\AbstractRequest;

class JSFlowGateway extends AbstractGateway
{
    /**
     * Only to be used with approved payment pages
     *
     * @param array $parameters
     *
     * @return Message\CreateCustomerRequest|AbstractRequest|Message\AbstractRequest|JSFlowGateway
     */
    public function createCustomer(array $parameters = [])
    {
        return $this->createRequest(Message\CreateCustomerRequest::class, $parameters);
    }

    /**
     * For use with the JS Flow process
     *
     * @param array $parameters
     *
     * @return Message\CreateCustomerBankAccountRequestFromToken|Message\AbstractRequest|JSFlowGateway
     */
    public function createBankAccount(array $parameters = [])
    {
        return $this->createRequest(Message\CreateCustomerBankAccountRequestFromToken::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\CreateMandateRequest|Message\AbstractRequest|JSFlowGateway
     */
    public function createMandate(array $parameters = [])
    {
        return $this->createRequest(Message\CreateMandateRequest::class, $parameters);
    }
}
