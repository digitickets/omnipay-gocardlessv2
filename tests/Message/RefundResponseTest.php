<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreateRefundRequest;
use Omnipay\GoCardlessV2\Message\RefundResponse;
use Omnipay\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    /**
     * @var CreateRefundRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreateRefundRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetRefundData()
    {
        $data = 'refundData';

        $response = new RefundResponse($this->request, $data);
        $this->assertEquals('refundData', $response->getRefundData());
    }

    public function testFailedRefundData()
    {
        $data = null;

        $response = new RefundResponse($this->request, $data);
        $this->assertNull($response->getRefundData());
    }
}
