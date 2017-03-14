<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * @method RedirectCompleteAuthoriseResponse send()
 */
class RedirectCompleteAuthoriseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = [
            'authorisationRequestId' => $this->getTransactionReference(),
            'params' => [
                'session_token' => $this->getTransactionId(),
            ],
        ];

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return RedirectCompleteAuthoriseResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->redirectFlows()->complete($data['authorisationRequestId'], $data);

        return $this->response = new RedirectCompleteAuthoriseResponse($this, $response);
    }
}
