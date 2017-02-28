<?php
namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Tests\TestCase;

class EventResponseTest extends TestCase
{
    /**
     * @var FindEventRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(FindEventRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetEventData()
    {
        $data = 'eventData';

        $response = new EventResponse($this->request, $data);
        $this->assertEquals('eventData', $response->getEventData());
    }

    public function testFailedEventData()
    {
        $data = null;

        $response = new EventResponse($this->request, $data);
        $this->assertNull($response->getEventData());
    }

}
