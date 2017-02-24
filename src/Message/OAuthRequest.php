<?php
namespace Omnipay\GoCardlessV2\Message;

class OAuthRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'params'=>array(
                'response_type'=>'code',
                'client_id'=>$this->getMerchantId(),
                'scope'=>$this->getOAuthScope(),
                'redirect_uri'=>$this->getReturnUrl(),
                'state' => $this->getTransactionId(),
                'prefill'=>array('email'=>$this->getEmail())
            ),
            'redirectURL'=>$this->getOAuthUrl().'/authorize'
        );
        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return OAuthResponse
     */
    public function sendData($data)
    {
        return $this->response = new OAuthResponse ($this, $data);
    }
}
