<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Refund;
use GoCardlessPro\Services\RefundsService;
use Omnipay\GoCardlessV2\Message\FindRefundRequest;
use Omnipay\GoCardlessV2\Message\RefundResponse;
use Omnipay\Tests\TestCase;

class FindRefundRequestTest extends TestCase
{
    /**
     * @var FindRefundRequest
     */
    private $request;

    /**
     * @var array fully populated sample refund data to drive test
     */
    private $sampleData = [
        'transactionReference' => 'CU123123123',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'refunds',
                ]
            )
            ->getMock();
        $refundService = $this->getMockBuilder(RefundsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'get',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('refunds')
            ->will($this->returnValue($refundService));
        $refundService->expects($this->any())
            ->method('get')
            ->will($this->returnCallback([$this, 'refundGet']));

        $this->request = new FindRefundRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame([], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['transactionReference'], $this->request->getTransactionReference());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls refund create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleRefund).
        $result = $this->request->send();
        $this->assertInstanceOf(RefundResponse::class, $result);
    }

    // Assert the refund get method is being handed the refundId
    public function refundGet($data)
    {
        $this->assertEquals($this->sampleData['transactionReference'], $data);

        return $this->getMockBuilder(Refund::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
