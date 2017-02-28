<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Mandate;
use GoCardlessPro\Services\MandatesService;
use Omnipay\Tests\TestCase;

class FindMandateRequestTest extends TestCase
{
    /**
     * @var FindMandateRequest
     */
    private $request;

    /**
     * @var array fully populated sample mandate data to drive test
     */
    private $sampleData = array(
        'mandateId' => 'CU123123123',
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'mandates'
            ))
            ->getMock();
        $mandateService = $this->getMockBuilder(MandatesService::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'get'
            ))
            ->getMock();

        $gateway->expects($this->any())
            ->method('mandates')
            ->will($this->returnValue($mandateService));
        $mandateService->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(array($this, 'mandateGet')));

        $this->request = new FindMandateRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame(array(), $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['mandateId'], $this->request->getMandateId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls mandate create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleMandate).
        $result = $this->request->send($this->request->getData());
        $this->assertInstanceOf(MandateResponse::class, $result);
    }

    // Assert the mandate get method is being handed the mandateId
    public function mandateGet($data){

         $this->assertEquals($this->sampleData['mandateId'], $data);

        return $this->getMockBuilder(Mandate::class)
                ->disableOriginalConstructor()
                ->getMock();
    }
}
