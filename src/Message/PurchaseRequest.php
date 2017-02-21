<?php
namespace Omnipay\GoCardlessV2\Message;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class PurchaseRequest extends AuthorizeRequest
{
    public function getData()
    {
        $data = parent::getData();

        $data['options']['submitForSettlement'] = true;

        return $data;
    }
}
