<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method RedirectAuthoriseResponse send()
 */
class RedirectAuthoriseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = [
            'description' => $this->getDescription(),
            'session_token' => $this->getTransactionId(),
            'success_redirect_url' => $this->getReturnUrl(),
        ];
        if ($this->getCreditorId()) {
            $data['links']['creditor'] = $this->getCreditorId();
        }

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return RedirectAuthoriseResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->redirectFlows()->create(['params' => $data]);

        return $this->response = new RedirectAuthoriseResponse($this, $response);
    }
}
