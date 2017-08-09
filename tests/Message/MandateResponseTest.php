<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreateMandateRequest;
use Omnipay\GoCardlessV2\Message\MandateResponse;
use Omnipay\Tests\TestCase;

class MandateResponseTest extends TestCase
{
    /**
     * @var CreateMandateRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreateMandateRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetMandateData()
    {
        $data = json_decode('{"id":"MA1234"}');

        $response = new MandateResponse($this->request, $data);
        $this->assertEquals($data, $response->getMandateData());
        $this->assertEquals('MA1234', $response->getMandateReference());
    }

    public function testFailedMandateData()
    {
        $data = null;

        $response = new MandateResponse($this->request, $data);
        $this->assertNull($response->getMandateData());
    }
}
