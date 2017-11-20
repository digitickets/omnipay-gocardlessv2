<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreateSubscriptionRequest;
use Omnipay\GoCardlessV2\Message\SubscriptionResponse;
use Omnipay\GoCardlessV2\Message\UpcomingPaymentResponse;
use Omnipay\Tests\TestCase;

class SubscriptionResponseTest extends TestCase
{
    /**
     * @var CreateSubscriptionRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreateSubscriptionRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetSubscriptionData()
    {
        $rawData = '{
    "id": "SB123",
    "created_at": "2014-10-20T17:01:06.000Z",
    "amount": 2500,
    "currency": "GBP",
    "status": "active",
    "name": "Monthly Magazine",
    "start_date": "2014-11-03",
    "end_date": null,
    "interval": 1,
    "interval_unit": "monthly",
    "day_of_month": 1,
    "month": null,
    "payment_reference": null,
    "upcoming_payments": [
      { "charge_date": "2014-11-03", "amount": 2500 },
      { "charge_date": "2014-12-01", "amount": 2500 },
      { "charge_date": "2015-01-02", "amount": 2500 },
      { "charge_date": "2015-02-02", "amount": 2500 },
      { "charge_date": "2015-03-02", "amount": 2500 },
      { "charge_date": "2015-04-01", "amount": 2500 },
      { "charge_date": "2015-05-01", "amount": 2500 },
      { "charge_date": "2015-06-01", "amount": 2500 },
      { "charge_date": "2015-07-01", "amount": 2500 },
      { "charge_date": "2015-08-03", "amount": 2500 }
    ],
    "metadata": {
      "bar": "foo"
    },
    "links": {
      "mandate": "MA123"
    }
  }';
        $data = json_decode($rawData);
        $array = json_decode($rawData, true);
        $response = new SubscriptionResponse($this->request, $data);
        $this->assertEquals($data, $response->getSubscriptionData());
        $this->assertEquals($array['id'], $response->getSubscriptionReference());
        $this->assertEquals($array['name'], $response->getName());
        $this->assertEquals($array['amount'] / 100, $response->getAmount());
        $this->assertEquals(\DateTime::createFromFormat('!Y-m-d?H:i:s.u?', $array['created_at']), $response->getCreatedAt());
        $this->assertEquals(\DateTime::createFromFormat('!Y-m-d', $array['start_date']), $response->getStartDate());
        $this->assertEquals(\DateTime::createFromFormat('!Y-m-d', $array['end_date']), $response->getEndDate());
        $this->assertEquals($array['currency'], $response->getCurrency());
        $this->assertEquals($array['interval'], $response->getInterval());
        $this->assertEquals($array['interval_unit'], $response->getIntervalUnit());
        $this->assertEquals($array['day_of_month'], $response->getDayOfMonth());
        $this->assertEquals($array['month'], $response->getMonth());
        $this->assertEquals($array['payment_reference'], $response->getPaymentReference());
        $this->assertEquals($array['status'], $response->getStatus());
        foreach($array['links'] as $link=>$value){
            $func = "getLink".ucfirst($link);
            $this->assertEquals($value, $response->{$func}());
        }
        foreach($array['metadata'] as $link=>$value){
            $this->assertEquals($value, $response->getMetaField($link));
        }
        $upcoming = $response->getUpcomingPayments();
        foreach($array['upcoming_payments'] as $id=>$futurePayment){
            /** @var UpcomingPaymentResponse $upcomingPaymentResponse */
            $upcomingPaymentResponse = $upcoming[$id];
            $this->assertInstanceOf(UpcomingPaymentResponse::class, $upcomingPaymentResponse);
            $this->assertEquals(round($futurePayment['amount']/100,2), $upcomingPaymentResponse->getAmount());
            $this->assertEquals(\DateTime::createFromFormat('!Y-m-d', $futurePayment['charge_date']), $upcomingPaymentResponse->getChargeDate());
        }
    }

    public function testFailedSubscriptionData()
    {
        $data = null;

        $response = new SubscriptionResponse($this->request, $data);
        $this->assertNull($response->getSubscriptionData());
    }

    public function outstandingProvider()
    {
        return [
            [SubscriptionResponse::PENDING_CUSTOMER_APPROVAL, true],
            [SubscriptionResponse::CUSTOMER_APPROVAL_DENIED, false],
            [SubscriptionResponse::ACTIVE, true],
            [SubscriptionResponse::FINISHED, false],
            [SubscriptionResponse::CANCELLED, false],
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
            "status" => $status,
        ];
        $raw = json_encode($array);
        $data = json_decode($raw);

        $response = new SubscriptionResponse($this->request, $data);
        $this->assertEquals($outcome, $response->isOutstanding(), $status);
    }
}
