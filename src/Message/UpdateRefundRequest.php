<?php
namespace Omnipay\GoCardlessV2\Message;


class UpdateRefundRequest extends AbstractRequest
{
    public function getData()
    {
        return array(
            'refundData' => array("params" => array('metadata' => $this->getPaymentMetaData())),
            'refundId' => $this->getTransactionReference(),
        );
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return RefundResponse
     */
    public function sendData($data)
    {
        $response = $this->gocardless->refunds()->update($data['refundId'], $data['refundData']);

        return $this->response = new RefundResponse($this, $response);
    }
}
