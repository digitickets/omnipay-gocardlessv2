<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\RedirectFlow;

class RedirectCompleteAuthoriseResponse extends AbstractResponse
{
    /**
     * @var RedirectFlow|null
     */
    protected $data;

    /**
     * @return string|null
     */
    public function getMandateId()
    {
        if (isset($this->data)) {
            return $this->data->links->mandate;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        if (isset($this->data)) {
            return $this->data->links->customer;
        }

        return null;
    }
}
