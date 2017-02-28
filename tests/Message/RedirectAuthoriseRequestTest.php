<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\RedirectFlow;
use Omnipay\Tests\TestCase;

class RedirectAuthoriseRequestTest extends TestCase
{
    /**
     * @var RedirectAuthoriseRequest
     */
    private $request;

    private $sampleAuthorise = array(
        'description' => 'CB1231235413',
        'transactionId' => 'CR783472',
        'returnUrl' => 'https://this.site.com/return',
        'creditorId' => 'CR123123123',
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'redirectFlows',
                )
            )
            ->getMock();
        $authoriseService = $this->getMockBuilder(RedirectFlow::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'create',
                )
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('redirectFlows')
            ->will($this->returnValue($authoriseService));
        $authoriseService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback(array($this, 'authoriseCreate')));

        $this->request = new RedirectAuthoriseRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleAuthorise);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data['description'] = $this->sampleAuthorise['description'];
        $data['session_token'] = $this->sampleAuthorise['transactionId'];
        $data['success_redirect_url'] = $this->sampleAuthorise['returnUrl'];
        $data['links']['creditor'] = $this->sampleAuthorise['creditorId'];
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleAuthorise['description'], $this->request->getDescription());
        $this->assertSame($this->sampleAuthorise['transactionId'], $this->request->getTransactionId());
        $this->assertSame($this->sampleAuthorise['returnUrl'], $this->request->getReturnUrl());
        $this->assertSame($this->sampleAuthorise['creditorId'], $this->request->getCreditorId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls authorise create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleAuthorise).
        $result = $this->request->send();
        $this->assertInstanceOf(RedirectAuthoriseResponse::class, $result);
    }

    // Assert the authorise create method is being handed the correct parameters
    public function authoriseCreate($data)
    {

        $this->assertEquals(array('params' => $this->request->getData()), $data);

        return $this->getMockBuilder(RedirectFlow::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
