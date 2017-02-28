<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use Guzzle\Http\Message\Request;
use Omnipay\Tests\TestCase;

class OAuthConfirmRequestTest extends TestCase
{
    /**
     * @var OAuthConfirmRequest
     */
    private $request;

    private $sampleAuthorise = array(
        'merchantId' => 'CB1231235413',
        'oAuthSecret' => 'read_only',
        'returnUrl' => 'https://this.site.com/return',
        'transactionReference' => 'CR123123123',
    );

    private $jsonReturn = '{"links":{"mandate":"mandateVal","customer":"customerVal"}}';

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClient = $this->getMockBuilder(\Guzzle\Http\Client::class)
            ->disableOriginalConstructor()
            ->setMethods(array('post'))
            ->getMock();
        $post = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(array('send'))
            ->getMock();

        $send = $this->getMockBuilder('Response')
            ->disableOriginalConstructor()
            ->setMethods(array('getBody'))
            ->getMock();


        $send->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($this->jsonReturn));
        $post->expects($this->any())
            ->method('send')
            ->will($this->returnValue($send));
        $httpClient->expects($this->any())
            ->method('post')
            ->will($this->returnValue($post));

        $this->request = new OAuthConfirmRequest($httpClient, $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleAuthorise);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = array(
            'params' => array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->sampleAuthorise['merchantId'],
                'client_secret' => $this->sampleAuthorise['oAuthSecret'],
                'redirect_uri' => $this->sampleAuthorise['returnUrl'],
                'code' => $this->sampleAuthorise['transactionReference'],
            ),
            'url' => $this->request->getOAuthUrl().'/access_token',
        );
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleAuthorise['merchantId'], $this->request->getMerchantId());
        $this->assertSame($this->sampleAuthorise['oAuthSecret'], $this->request->getOAuthSecret());
        $this->assertSame($this->sampleAuthorise['returnUrl'], $this->request->getReturnUrl());
        $this->assertSame($this->sampleAuthorise['transactionReference'], $this->request->getTransactionReference());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls authorise create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleAuthorise).
        $result = $this->request->send();
        $this->assertInstanceOf(OAuthConfirmResponse::class, $result);
    }

}
