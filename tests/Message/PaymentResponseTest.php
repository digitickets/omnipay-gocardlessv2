<?php
namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Tests\TestCase;

class PaymentResponseTest extends TestCase
{
    /**
     * @var CreatePaymentRequest
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
