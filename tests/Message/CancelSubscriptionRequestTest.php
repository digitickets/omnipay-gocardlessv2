<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Subscription;
use GoCardlessPro\Services\SubscriptionsService;
use Omnipay\Tests\TestCase;

class CancelSubscriptionRequestTest extends TestCase
{
    /**
     * @var CancelSubscriptionRequest
     */
    private $request;

    /**
     * @var array fully populated sample subscription data to drive test
     */
    private $sampleData = array(
        'subscriptionId' => 'CU123123123',
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'subscriptions',
                )
            )
            ->getMock();
        $subscriptionService = $this->getMockBuilder(SubscriptionsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'cancel',
                )
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('subscriptions')
            ->will($this->returnValue($subscriptionService));
        $subscriptionService->expects($this->any())
            ->method('cancel')
            ->will($this->returnCallback(array($this, 'subscriptionGet')));

        $this->request = new CancelSubscriptionRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame(array(), $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['subscriptionId'], $this->request->getSubscriptionId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls subscription create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleSubscription).
        $result = $this->request->send();
        $this->assertInstanceOf(SubscriptionResponse::class, $result);
    }

    // Assert the subscription get method is being handed the subscriptionId
    public function subscriptionGet($data)
    {

        $this->assertEquals($this->sampleData['subscriptionId'], $data);

        return $this->getMockBuilder(Subscription::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
