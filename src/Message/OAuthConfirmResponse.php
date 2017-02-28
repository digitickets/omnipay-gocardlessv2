<?php

namespace Omnipay\GoCardlessV2\Message;

class OAuthConfirmResponse extends AbstractResponse
{
    /**
     * @return string|null
     */
    public function getAccessToken()
    {
        if (isset($this->data->access_token)) {
            return $this->data->access_token;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getScope()
    {
        if (isset($this->data->scope)) {
            return $this->data->scope;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getTokenType()
    {
        if (isset($this->data->token_type)) {
            return $this->data->token_type;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getOrganisationId()
    {
        if (isset($this->data->organisation_id)) {
            return $this->data->organisation_id;
        }

        return null;
    }
}
