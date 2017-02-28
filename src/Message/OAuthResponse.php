<?php

namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class OAuthResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->data['redirect_url'];
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return $this->data['params'];
    }
}
