<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Mandate;
use GoCardlessPro\Services\MandatesService;
use Omnipay\Tests\TestCase;

class UpdateMandateRequestTest extends TestCase
{
    /**
     * @var UpdateMandateRequest
     */
    private $request;

    /**
     * @var array fully populated sample mandate data to drive test
     */
    private $sampleData = array(
        'mandateId' => 'CU123123123',
        'mandateData' => array(
            'metaData' => array(
                'meta1' => 'Lorem Ipsom Dolor Est',
                'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
                'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
            ),
        ),
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'mandates',
                )
            )
            ->getMock();
        $mandateService = $this->getMockBuilder(MandatesService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'update',
                )
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('mandates')
            ->will($this->returnValue($mandateService));
        $mandateService->expects($this->any())
            ->method('update')
            ->will($this->returnCallback(array($this, 'mandateGet')));

        $this->request = new UpdateMandateRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = array(
            'mandateData' => array('params' => $this->sampleData['mandateData']),
            'mandateId' => $this->sampleData['mandateId'],
        );
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['mandateId'], $this->request->getMandateId());
        $this->assertSame($this->sampleData['mandateData'], $this->request->getMandateData());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls mandate create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleMandate).
        $result = $this->request->send();
        $this->assertInstanceOf(MandateResponse::class, $result);
    }

    // Assert the mandate get method is being handed the mandateId
    public function mandateGet($id, $data)
    {

        $this->assertEquals($this->sampleData['mandateId'], $id);
        $this->assertEquals($this->request->getData()['mandateData'], $data);

        return $this->getMockBuilder(Mandate::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
