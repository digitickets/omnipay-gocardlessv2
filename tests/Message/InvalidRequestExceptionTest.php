<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use Omnipay\GoCardlessV2\Message\CancelMandateRequest;
use Omnipay\GoCardlessV2\Message\ErrorResponse;
use Omnipay\Tests\TestCase;

class InvalidRequestExceptionTest extends TestCase
{
    /**
     * @var CancelMandateRequest
     */
    private $request;

    /**
     * @var array fully populated sample mandate data to drive test
     */
    private $sampleData = [
        'mandateReference' => 'CU123123123',
    ];

    public function setUp()
    {
        $gateway = new Client(['access_token' => 'foobar', 'environment' => 'sandbox']);

        $this->request = new CancelMandateRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testExceptionThrown()
    {
        // this should be blank
        $response = $this->request->send();
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->assertEquals(false, $response->isSuccessful());
        $this->assertEquals('The access token you\'ve used is not a valid sandbox API access token', $response->getMessage());
    }
}
