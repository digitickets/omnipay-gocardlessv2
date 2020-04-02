<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Core\Paginator;
use GoCardlessPro\Services\PaymentsService;
use Omnipay\GoCardlessV2\Message\FindPaymentsBySubscriptionRequest;
use Omnipay\GoCardlessV2\Message\PaymentSearchResponse;
use Omnipay\Tests\TestCase;

class FindPaymentsBySubscriptionRequestTest extends TestCase
{
    /**
     * @var FindPaymentsBySubscriptionRequest
     */
    private $request;

    /**
     * @var array fully populated sample payment data to drive test
     */
    private $sampleData = [
        'subscriptionId' => 'SB123123123',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'payments',
                ]
            )
            ->getMock();
        $paymentService = $this->getMockBuilder(PaymentsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'all',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('payments')
            ->will($this->returnValue($paymentService));
        $paymentService->expects($this->any())
            ->method('all')
            ->will($this->returnCallback([$this, 'paymentAll']));

        $this->request = new FindPaymentsBySubscriptionRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame(['subscription' => $this->sampleData['subscriptionId']], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['subscriptionId'], $this->request->getSubscriptionId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls payment create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->samplePayment).
        $result = $this->request->send();
        $this->assertInstanceOf(PaymentSearchResponse::class, $result);
    }

    // Assert the payment get method is being handed the paymentId
    public function paymentAll($data)
    {
        $this->assertEquals(["params" => ['subscription' => $this->sampleData['subscriptionId']]], $data);

        return $this->getMockBuilder(Paginator::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
