<?php

namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

abstract class AbstractResponse extends BaseAbstractResponse
{
    public function isSuccessful()
    {   // failures are errors and never get here!
        return true;
    }
}
