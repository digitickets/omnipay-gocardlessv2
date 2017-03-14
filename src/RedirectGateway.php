<?php

namespace Omnipay\GoCardlessV2;

class RedirectGateway extends AbstractGateway
{
    /**
     * @param array $parameters
     *
     * @return Message\RedirectAuthoriseRequest|Message\AbstractRequest|RedirectGateway
     */
    public function authoriseRequest(array $parameters = [])
    {
        return $this->createRequest(Message\RedirectAuthoriseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\RedirectCompleteAuthoriseRequest|Message\AbstractRequest|RedirectGateway
     */
    public function completeAuthoriseRequest(array $parameters = [])
    {
        return $this->createRequest(Message\RedirectCompleteAuthoriseRequest::class, $parameters);
    }
}
