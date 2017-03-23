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
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMandateReference()
    {
        return $this->data->id;
    }
}
