<?php

namespace Omnipay\GoCardlessV2;

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
        $this->assertInstanceOf(Message\RedirectAuthoriseRequest::class, $request);
    }

    public function testCompleteAuthoriseRequest()
    {
        $request = $this->gateway->completeAuthoriseRequest();
        $this->assertInstanceOf(Message\RedirectCompleteAuthoriseRequest::class, $request);
    }

}
