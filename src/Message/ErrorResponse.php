<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Core\Exception\GoCardlessProException;

class ErrorResponse extends AbstractResponse
{
    /**
     * @var GoCardlessProException|\Exception
     */
    private $error;

    /**
     * ErrorResponse constructor.
     *
     * @param \Omnipay\Common\Message\RequestInterface $request
     * @param \Exception $exception
     */
    public function __construct($request, $exception)
    {
        parent::__construct($request, $exception);
        $this->error = $exception;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function getMessage()
    {
        return $this->error->getMessage();
    }
}
