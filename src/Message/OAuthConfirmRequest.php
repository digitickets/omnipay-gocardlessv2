<?php
namespace Omnipay\GoCardlessV2\Message;

class OAuthConfirmRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'params' => array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->getMerchantId(),
                'client_secret' => $this->getOAuthSecret(),
                'redirect_uri' => $this->getReturnUrl(),
                'code' => $this->getTransactionReference(),
            ),
            'url' => $this->getOAuthUrl().'/access_token',
        );

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return OAuthConfirmResponse
     */
    public function sendData($data)
    {

        $httpResponse = $this->httpClient->post($data['url'], null, $data['params'])->send();

        // The body is a string.
        $body = $httpResponse->getBody();

        // Split into lines.
        $data = json_decode($body);

        return $this->response = new OAuthConfirmResponse ($this, $data);
    }

}
