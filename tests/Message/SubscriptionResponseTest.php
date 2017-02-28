<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreateSubscriptionRequest;
use Omnipay\GoCardlessV2\Message\SubscriptionResponse;
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
        $data = 'subscriptionData';

        $response = new SubscriptionResponse($this->request, $data);
        $this->assertEquals('subscriptionData', $response->getSubscriptionData());
    }

    public function testFailedSubscriptionData()
    {
        $data = null;

        $response = new SubscriptionResponse($this->request, $data);
        $this->assertNull($response->getSubscriptionData());
    }
}
