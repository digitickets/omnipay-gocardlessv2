<?php

namespace Omnipay\GoCardlessV2;

class RedirectGateway extends AbstractGateway
{
    /**
     * @param array $parameters
     *
     * @return Message\RedirectAuthoriseResponse|Message\AbstractRequest|RedirectGateway
     */
    public function authoriseRequest(array $parameters = [])
    {
        return $this->createRequest(Message\RedirectAuthoriseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\RedirectCompleteAuthoriseResponse|Message\AbstractRequest|RedirectGateway
     */
    public function completeAuthoriseRequest(array $parameters = [])
    {
        return $this->createRequest(Message\RedirectCompleteAuthoriseRequest::class, $parameters);
    }
}
