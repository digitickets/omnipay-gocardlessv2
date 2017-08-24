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

    public function testGetCustomerData()
    {
        $array = [
            "id" => "P123",
            "amount" => "1000",
            "amount_refunded" => "100",
            "created_at" => "2017-01-01T12:01:02.345678",
            "charge_date" => "2017-01-01T12:01:02.345678",
            "currency" => "GBP",
            "reference" => "Some Reference",
            "status" => "good, bad or ugly",
        ];
        $raw = json_encode($array);
        $data = json_decode($raw);

        $response = new PaymentResponse($this->request, $data);

        $this->assertEquals($data, $response->getPaymentData());
        $this->assertEquals($array['id'], $response->getPaymentReference());
        $this->assertEquals($array['amount']/100, $response->getAmount());
        $this->assertEquals($array['amount_refunded']/100, $response->getAmountRefunded());
        $this->assertEquals( \DateTime::createFromFormat('!Y-m-d?H:i:s.u?',$array['created_at']), $response->getCreatedAt());
        $this->assertEquals( \DateTime::createFromFormat('!Y-m-d?H:i:s.u?',$array['charge_date']), $response->getChargeDate());
        $this->assertEquals($array['currency'], $response->getCurrency());
        $this->assertEquals($array['currency'], $response->getDescription());
        $this->assertEquals($array['reference'], $response->getReference());
        $this->assertEquals($array['status'], $response->getStatus());
    }
}
