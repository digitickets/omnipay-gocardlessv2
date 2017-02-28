<?php

namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Tests\TestCase;

class OAuthResponseTest extends TestCase
{
    /**
     * @var OAuthRequest
     */
    private $request;

    private $data;

    public function setUp()
    {
        $this->data = array(
            "params" => array("some" => "params"),
            "redirect_url" => "https://this.site.com/redirect",
        );
        $this->request = $this->getMockBuilder(OAuthRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testFixedFunctions()
    {
        $response = new OAuthResponse($this->request, $this->data);
        $this->assertEquals(false, $response->isSuccessful());
        $this->assertEquals(true, $response->isRedirect());
        $this->assertEquals('GET', $response->getRedirectMethod());
    }

    public function testReturnedValues()
    {
        $response = new OAuthResponse($this->request, $this->data);
        $this->assertEquals($this->data['params'], $response->getRedirectData());
        $this->assertEquals($this->data['redirect_url'], $response->getRedirectUrl());
    }

}
