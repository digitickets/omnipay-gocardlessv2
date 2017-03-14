<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\Mandate;

class MandateResponse extends AbstractResponse
{
    /**
     * @return Mandate|null
     */
    public function getMandateData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        return null;
    }
}
