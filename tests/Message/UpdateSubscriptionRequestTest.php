<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Subscription;
use GoCardlessPro\Services\SubscriptionsService;
use Omnipay\Tests\TestCase;

class UpdateSubscriptionRequestTest extends TestCase
{
    /**
     * @var UpdateSubscriptionRequest
     */
    private $request;

    /**
     * @var array fully populated sample subscription data to drive test
     */
    private $sampleData = array(
        'subscriptionId' => 'CU123123123',
        'paymentDescription' => 'This is a lovely name for a subscription',
        'subscriptionMetaData' => array(
            'meta1' => 'Lorem Ipsom Dolor Est',
            'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
            'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
        ),
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'subscriptions'
            ))
            ->getMock();
        $subscriptionService = $this->getMockBuilder(SubscriptionsService::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'update'
            ))
            ->getMock();

        $gateway->expects($this->any())
            ->method('subscriptions')
            ->will($this->returnValue($subscriptionService));
        $subscriptionService->expects($this->any())
            ->method('update')
            ->will($this->returnCallback(array($this, 'subscriptionGet')));

        $this->request = new UpdateSubscriptionRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = array('subscriptionData' => array('name'=>$this->sampleData['paymentDescription'],'metadata'=>$this->sampleData['subscriptionMetaData']),
            'subscriptionId' => $this->sampleData['subscriptionId'],);
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['subscriptionId'], $this->request->getSubscriptionId());
        $this->assertSame($this->sampleData['subscriptionMetaData'], $this->request->getSubscriptionMetaData());
        $this->assertSame($this->sampleData['paymentDescription'], $this->request->getPaymentDescription());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls subscription create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleSubscription).
        $result = $this->request->sendData($this->request->getData());
        $this->assertInstanceOf(SubscriptionResponse::class, $result);
    }

    // Assert the subscription get method is being handed the subscriptionId
    public function subscriptionGet($id, $data){

        $this->assertEquals($this->sampleData['subscriptionId'], $id);
        $this->assertEquals($this->request->getData()['subscriptionData'], $data);

        return $this->getMockBuilder(Subscription::class)
                ->disableOriginalConstructor()
                ->getMock();
    }
}
