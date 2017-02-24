<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Subscription;
use GoCardlessPro\Services\SubscriptionsService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class CreateSubscriptionRequestTest extends TestCase
{
    /**
     * @var CreateSubscriptionRequest
     */
    private $request;

    /**
     * @var array fully populated sample subscription data to drive test
     */
    private $sampleSubscription = array(
        'amount' => 12.99,
        'currency' => 'GBP',
        'subscriptionDayOfMonth' => '1',
        'subscriptionInterval' => '1',
        'subscriptionIntervalUnit' => 'month',
        'subscriptionMetaData' => array(
            'meta1' => 'Lorem Ipsom Dolor Est',
            'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
            'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
        ),
        'subscriptionMonth' => '1',
        'paymentDescription' => 'bacs subscription',
        'reference' => '13wekjhewe123n3hjd8',
        'paymentDate' => '2017-01-01',
        'mandateId' => 'CB1231235413',
        'subscriptionCount' => '12',
        'subscriptionEndDate' => '2018-01-01',
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
                    'create',
                )
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('subscriptions')
            ->will($this->returnValue($subscriptionService));
        $subscriptionService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback(array($this, 'subscriptionCreate')));

        $this->request = new CreateSubscriptionRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
    }

    public function testGetDataException()
    {
        $requestData = $this->sampleSubscription;
        unset($requestData['subscriptionCount'], $requestData['subscriptionEndDate']);
        $this->request->initialize($requestData);
        $this->setExpectedException(InvalidRequestException::class, 'The subscription count or end date should be set.');
        $this->request->getData();
    }

    public function testGetDataReturnsCorrectArrayWithCount()
    {
        $requestData = $this->sampleSubscription;
        unset($requestData['subscriptionEndDate']);
        $this->request->initialize($requestData);
        $data = array(
            'amount' => $requestData['amount'] * 100,
            'currency' => $requestData['currency'],
            'day_of_month' => $requestData['subscriptionDayOfMonth'],
            'interval' => $requestData['subscriptionInterval'],
            'interval_unit' => $requestData['subscriptionIntervalUnit'],
            'metadata' => $requestData['subscriptionMetaData'],
            'month' => $requestData['subscriptionMonth'],
            'name' => $requestData['paymentDescription'],
            'payment_reference' => $requestData['reference'],
            'start_date' => $requestData['paymentDate'],
            'links' => array('mandate' => $requestData['mandateId']),
            'count' => $requestData['subscriptionCount'],
        );

        $this->assertEquals($data, $this->request->getData());
    }

    public function testGetDataReturnsCorrectArrayWithEndDate()
    {
        $requestData = $this->sampleSubscription;
        unset($requestData['subscriptionCount']);
        $this->request->initialize($requestData);
        $data = array(
            'amount' => $requestData['amount'] * 100,
            'currency' => $requestData['currency'],
            'day_of_month' => $requestData['subscriptionDayOfMonth'],
            'interval' => $requestData['subscriptionInterval'],
            'interval_unit' => $requestData['subscriptionIntervalUnit'],
            'metadata' => $requestData['subscriptionMetaData'],
            'month' => $requestData['subscriptionMonth'],
            'name' => $requestData['paymentDescription'],
            'payment_reference' => $requestData['reference'],
            'start_date' => $requestData['paymentDate'],
            'links' => array('mandate' => $requestData['mandateId']),
            'end_date' => $requestData['subscriptionEndDate'],
        );

        $this->assertEquals($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->request->initialize($this->sampleSubscription);
        foreach ($this->sampleSubscription as $field => $value) {
            $function = 'get'.ucfirst($field);
            $this->assertEquals($value, $this->request->{$function}());
        }
    }

    public function testSendDataReturnsCorrectType()
    {
        $this->request->initialize($this->sampleSubscription);
        // this will trigger additional validation as the sendData method calls subscription create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleSubscription).
        $result = $this->request->sendData($this->request->getData());
        $this->assertInstanceOf(SubscriptionResponse::class, $result);
    }

    // Assert the subscription create method is being handed the correct parameters
    public function subscriptionCreate($data)
    {

        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(Subscription::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
