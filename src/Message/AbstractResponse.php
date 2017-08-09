<?php

namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

abstract class AbstractResponse extends BaseAbstractResponse
{
    public function isSuccessful()
    {   // failures are errors and never get here!
        return true;
    }

    /**
     * attempt to return the fieldName from the metadata on the response object
     * If not present return null.
     *
     * @param string $fieldName
     *
     * @return null|mixed
     */
    public function getMetaField($fieldName)
    {
        if (property_exists($this->data, 'metadata') && property_exists($this->data->metadata, $fieldName)) {
            return $this->data->metadata->{$fieldName};
        } else {
            return null;
        }
    }

    /**
     * attempt to return the link id from the links on the response object
     * If not present return null.
     *
     * @param string $linkType
     *
     * @return null|mixed
     */
    public function getLinkField($linkType)
    {
        if (property_exists($this->data, 'links') && property_exists($this->data->links, $linkType)) {
            return $this->data->links->{$linkType};
        } else {
            return null;
        }
    }
}
