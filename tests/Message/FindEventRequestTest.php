<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Event;
use GoCardlessPro\Services\EventsService;
use Omnipay\Tests\TestCase;

class FindEventRequestTest extends TestCase
{
    /**
     * @var FindEventRequest
     */
    private $request;

    /**
     * @var array fully populated sample event data to drive test
     */
    private $sampleData = array(
        'eventId' => 'CU123123123',
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'events'
            ))
            ->getMock();
        $eventService = $this->getMockBuilder(EventsService::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'get'
            ))
            ->getMock();

        $gateway->expects($this->any())
            ->method('events')
            ->will($this->returnValue($eventService));
        $eventService->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(array($this, 'eventGet')));

        $this->request = new FindEventRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame(array(), $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['eventId'], $this->request->getEventId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls event create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleEvent).
        $result = $this->request->send($this->request->getData());
        $this->assertInstanceOf(EventResponse::class, $result);
    }

    // Assert the event get method is being handed the eventId
    public function eventGet($data){

         $this->assertEquals($this->sampleData['eventId'], $data);

        return $this->getMockBuilder(Event::class)
                ->disableOriginalConstructor()
                ->getMock();
    }
}
