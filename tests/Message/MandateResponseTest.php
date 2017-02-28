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
        $data = 'mandateData';

        $response = new MandateResponse($this->request, $data);
        $this->assertEquals('mandateData', $response->getMandateData());
    }

    public function testFailedMandateData()
    {
        $data = null;

        $response = new MandateResponse($this->request, $data);
        $this->assertNull($response->getMandateData());
    }
}
