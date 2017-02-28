<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\Event;

class EventResponse extends AbstractResponse
{
    /**
     * @return Event|null
     */
    public function getEventData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }
}
