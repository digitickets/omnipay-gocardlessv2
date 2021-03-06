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
            "description" => "Some Reference",
            "status" => "good, bad or ugly",
            "metadata" => [
                "foo" => "bar",
            ],
            "links" => [
                "mandate" => "MD123",
                "creditor" => "CR123",
                "payout" => "P123",
                "subscription" => "SB123412"
            ],
        ];
        $raw = json_encode($array);
        $data = json_decode($raw);

        $response = new PaymentResponse($this->request, $data);

        $this->assertEquals($data, $response->getPaymentData());
        $this->assertEquals($array['id'], $response->getPaymentReference());
        $this->assertEquals($array['amount'] / 100, $response->getAmount());
        $this->assertEquals($array['amount_refunded'] / 100, $response->getAmountRefunded());
        $this->assertEquals(\DateTime::createFromFormat('!Y-m-d?H:i:s.u?', $array['created_at']), $response->getCreatedAt());
        $this->assertEquals(\DateTime::createFromFormat('!Y-m-d', $array['charge_date']), $response->getChargeDate());
        $this->assertEquals($array['currency'], $response->getCurrency());
        $this->assertEquals($array['description'], $response->getDescription());
        $this->assertEquals($array['reference'], $response->getReference());
        $this->assertEquals($array['status'], $response->getStatus());
        foreach($array['links'] as $link=>$value){
            $func = "getLink".ucfirst($link);
            $this->assertEquals($value, $response->{$func}());
        }
        foreach($array['metadata'] as $link=>$value){
            $this->assertEquals($value, $response->getMetaField($link));
        }
    }

    public function outstandingProvider()
    {
        return [
            [PaymentResponse::PENDING_CUSTOMER_APPROVAL, true],
            [PaymentResponse::PENDING_SUBMISSION, true],
            [PaymentResponse::SUBMITTED, true],
            [PaymentResponse::CONFIRMED, false],
            [PaymentResponse::PAID_OUT, false],
            [PaymentResponse::CANCELLED, false],
            [PaymentResponse::CUSTOMER_APPROVAL_DENIED, false],
            [PaymentResponse::FAILED, false],
            [PaymentResponse::CHARGED_BACK, false],
        ];
    }

    /**
     * @param $status
     * @param $outcome
     *
     * @dataProvider outstandingProvider
     */
    public function testIsOutstanding($status, $outcome)
    {
        $array = [
            "id" => "P123",
            "amount" => "1000",
            "amount_refunded" => "100",
            "created_at" => "2017-01-01T12:01:02.345678",
            "charge_date" => "2017-01-01T12:01:02.345678",
            "currency" => "GBP",
            "reference" => "Some Reference",
            "status" => $status,
        ];
        $raw = json_encode($array);
        $data = json_decode($raw);

        $response = new PaymentResponse($this->request, $data);
        $this->assertEquals($outcome, $response->isOutstanding(), $status);
    }
}
