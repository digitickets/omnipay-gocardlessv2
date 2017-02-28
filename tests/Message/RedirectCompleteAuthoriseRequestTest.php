<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\RedirectFlow;
use Omnipay\GoCardlessV2\Message\RedirectCompleteAuthoriseRequest;
use Omnipay\GoCardlessV2\Message\RedirectCompleteAuthoriseResponse;
use Omnipay\Tests\TestCase;

class RedirectCompleteAuthoriseRequestTest extends TestCase
{
    /**
     * @var RedirectCompleteAuthoriseRequest
     */
    private $request;

    private $sampleCompleteAuthorise = [
        'transactionId' => 'CR783472',
        'transactionReference' => 'CR123123123',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'redirectFlows',
                ]
            )
            ->getMock();
        $completeAuthoriseService = $this->getMockBuilder(RedirectFlow::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'complete',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('redirectFlows')
            ->will($this->returnValue($completeAuthoriseService));
        $completeAuthoriseService->expects($this->any())
            ->method('complete')
            ->will($this->returnCallback([$this, 'completeAuthoriseCreate']));

        $this->request = new RedirectCompleteAuthoriseRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCompleteAuthorise);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data['authorisationRequestId'] = $this->sampleCompleteAuthorise['transactionReference'];
        $data['params']['session_token'] = $this->sampleCompleteAuthorise['transactionId'];
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleCompleteAuthorise['transactionReference'], $this->request->getTransactionReference());
        $this->assertSame($this->sampleCompleteAuthorise['transactionId'], $this->request->getTransactionId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls completeAuthorise create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCompleteAuthorise).
        $result = $this->request->send();
        $this->assertInstanceOf(RedirectCompleteAuthoriseResponse::class, $result);
    }

    // Assert the completeAuthorise create method is being handed the correct parameters
    public function completeAuthoriseCreate($id, $data)
    {
        $this->assertEquals($this->request->getData()['authorisationRequestId'], $id);
        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(RedirectFlow::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
