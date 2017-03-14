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
     * @return Message\CustomerResponse|AbstractRequest|Message\AbstractRequest|JSFlowGateway
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
     * @return Message\CustomerBankAccountResponse|Message\AbstractRequest|JSFlowGateway
     */
    public function createCustomerBankAccountFromToken(array $parameters = [])
    {
        return $this->createRequest(Message\CreateCustomerBankAccountRequestFromToken::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\MandateResponse|Message\AbstractRequest|JSFlowGateway
     */
    public function createMandate(array $parameters = [])
    {
        return $this->createRequest(Message\CreateMandateRequest::class, $parameters);
    }
}
