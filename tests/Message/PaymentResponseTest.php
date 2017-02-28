<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreatePaymentRequest;
use Omnipay\GoCardlessV2\Message\PaymentResponse;
use Omnipay\Tests\TestCase;

class PaymentResponseTest extends TestCase
{
    /**
     * @var CreatePaymentRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreatePaymentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetPaymentData()
    {
        $data = 'paymentData';

        $response = new PaymentResponse($this->request, $data);
        $this->assertEquals('paymentData', $response->getPaymentData());
    }

    public function testFailedPaymentData()
    {
        $data = null;

        $response = new PaymentResponse($this->request, $data);
        $this->assertNull($response->getPaymentData());
    }
}
