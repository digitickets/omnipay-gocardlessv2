<?php

namespace Omnipay\GoCardlessV2\Message;

/**
 * Find Event Request
 *
 * @method EventResponses send()
 */
class FindEventRequest extends AbstractRequest
{
    public function getData()
    {
        return [];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data - completely ignored, included for consistency
     *
     * @return EventResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->events()->get($this->getEventId());

        return $this->response = new EventResponse($this, $response);
    }
}
