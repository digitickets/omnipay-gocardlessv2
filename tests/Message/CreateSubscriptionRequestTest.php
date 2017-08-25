<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Subscription;
use GoCardlessPro\Services\SubscriptionsService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\GoCardlessV2\Message\CreateSubscriptionRequest;
use Omnipay\GoCardlessV2\Message\SubscriptionResponse;
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
    private $sampleSubscription = [
        'amount' => 12.99,
        'currency' => 'GBP',
        'subscriptionDayOfMonth' => '1',
        'intervalCount' => '1',
        'interval' => 'monthly',
        'subscriptionMetaData' => [
            'meta1' => 'Lorem Ipsom Dolor Est',
            'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
            'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
        ],
        'subscriptionMonth' => '1',
        'paymentDescription' => 'bacs subscription',
        'reference' => '13wekjhewe123n3hjd8',
        'paymentDate' => '2017-01-01',
        'statementDescriptor' => 'CB1231235413',
        'subscriptionCount' => '12',
        'subscriptionEndDate' => '2018-01-01',
        'mandateReference' => 'MR12345',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'subscriptions',
                ]
            )
            ->getMock();
        $subscriptionService = $this->getMockBuilder(SubscriptionsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'create',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('subscriptions')
            ->will($this->returnValue($subscriptionService));
        $subscriptionService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback([$this, 'subscriptionCreate']));

        $this->request = new CreateSubscriptionRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
    }

    public function testSetInvalidPeriodDataException()
    {
        $requestData = $this->sampleSubscription;
        unset($requestData['subscriptionEndDate']);

        $requestData['interval'] = 'weekly';
        $this->request->initialize($requestData);
        $this->request->getData();

        $requestData['interval'] = 'monthly';
        $this->request->initialize($requestData);
        $this->request->getData();

        $requestData['interval'] = 'yearly';
        $this->request->initialize($requestData);
        $this->request->getData();

        $this->setExpectedException(InvalidRequestException::class, 'Interval must be one of weekly / monthly / yearly');

        $requestData['interval'] = 'month';
        $this->request->initialize($requestData);
        $this->request->getData();
    }

    public function testGetDataReturnsCorrectArrayWithCount()
    {
        $requestData = $this->sampleSubscription;
        unset($requestData['subscriptionEndDate']);
        $this->request->initialize($requestData);
        $data = [
            'params' => [
                'amount' => $requestData['amount'] * 100,
                'currency' => $requestData['currency'],
                'day_of_month' => $requestData['subscriptionDayOfMonth'],
                'interval' => $requestData['intervalCount'],
                'interval_unit' => $requestData['interval'],
                'metadata' => $requestData['subscriptionMetaData'],
                'month' => $requestData['subscriptionMonth'],
                'name' => $requestData['paymentDescription'],
                'payment_reference' => $requestData['statementDescriptor'],
                'start_date' => $requestData['paymentDate'],
                'links' => ['mandate' => $requestData['mandateReference']],
                'count' => $requestData['subscriptionCount'],
            ],
        ];

        $this->assertEquals($data, $this->request->getData());
    }

    public function testGetDataReturnsCorrectArrayWithEndDate()
    {
        $requestData = $this->sampleSubscription;
        unset($requestData['subscriptionCount']);
        $this->request->initialize($requestData);
        $data = [
            'params' => [
                'amount' => $requestData['amount'] * 100,
                'currency' => $requestData['currency'],
                'day_of_month' => $requestData['subscriptionDayOfMonth'],
                'interval' => $requestData['intervalCount'],
                'interval_unit' => $requestData['interval'],
                'metadata' => $requestData['subscriptionMetaData'],
                'month' => $requestData['subscriptionMonth'],
                'name' => $requestData['paymentDescription'],
                'payment_reference' => $requestData['statementDescriptor'],
                'start_date' => $requestData['paymentDate'],
                'links' => ['mandate' => $requestData['mandateReference']],
                'end_date' => $requestData['subscriptionEndDate'],
            ],
        ];

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
        $result = $this->request->send();
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

    public function testSetSubscriptionDayOfMonthError()
    {
        $subscription = $this->sampleSubscription;
        $subscription['subscriptionDayOfMonth']='No';
        $this->setExpectedException(\Exception::class);
        $this->request->initialize($subscription);
    }

    public function dayOfMonthProvider()
    {
        $output = [];
        for($i=1;$i<=31;++$i){
            $output[]=[$i];
        }
        return $output;
    }

    /**
     * @param int $i
     * @dataProvider dayOfMonthProvider
     */
    public function testSetSubscriptionDayOfMonth($i)
    {
        $subscription = $this->sampleSubscription;
        $subscription['subscriptionDayOfMonth']=$i;
        if($i>28){
            $expected = -1;
        }else{
            $expected = $i;
        }
        $this->request->initialize($subscription);
        $this->assertEquals($expected, $this->request->getSubscriptionDayOfMonth());
    }


    public function testStripNullsInGetData()
    {
        $data = $this->sampleSubscription;
        unset($data['subscriptionMetaData']);
        $this->request->initialize($data);
        $result = $this->request->getData();
        $this->assertArrayNotHasKey('subscriptionMetaData', $result['params']);
    }
}
