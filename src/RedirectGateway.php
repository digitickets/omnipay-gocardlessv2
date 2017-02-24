<?php


namespace Omnipay\GoCardlessV2;


class RedirectGateway extends AbstractGateway
{
    /**
     * @param array $parameters
     * @return Message\RedirectAuthoriseResponse
     */
    public function authoriseRequest(array $parameters = array())
    {
        return $this->createRequest(Message\RedirectAuthoriseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\RedirectCompleteAuthoriseResponse
     */
    public function completeAuthoriseRequest(array $parameters = array())
    {
        return $this->createRequest(Message\RedirectCompleteAuthoriseRequest::class, $parameters);
    }
}
