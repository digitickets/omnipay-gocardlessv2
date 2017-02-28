<?php

namespace Omnipay\GoCardlessV2Tests;

use Omnipay\GoCardlessV2\Message\RedirectAuthoriseRequest;
use Omnipay\GoCardlessV2\Message\RedirectCompleteAuthoriseRequest;
use Omnipay\GoCardlessV2\RedirectGateway;
use Omnipay\Tests\GatewayTestCase;

class RedirectGatewayTest extends GatewayTestCase
{
    /**
     * @var RedirectGateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new RedirectGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testAuthoriseRequest()
    {
        $request = $this->gateway->authoriseRequest();
        $this->assertInstanceOf(RedirectAuthoriseRequest::class, $request);
    }

    public function testCompleteAuthoriseRequest()
    {
        $request = $this->gateway->completeAuthoriseRequest();
        $this->assertInstanceOf(RedirectCompleteAuthoriseRequest::class, $request);
    }
}
