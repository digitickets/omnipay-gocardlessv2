<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Authorise;
use GoCardlessPro\Resources\RedirectFlow;
use GoCardlessPro\Services\AuthorisesService;
use Omnipay\Tests\TestCase;

class OAuthRequestTest extends TestCase
{
    /**
     * @var OAuthRequest
     */
    private $request;

    private $sampleAuthorise = array(
        'merchantId'=>'CB1231235413',
        'oAuthScope'=>'read_only',
        'returnUrl'=>'https://this.site.com/return',
        'transactionId'=>'CR123123123',
        'email'=>'joebloggs@mailinator.com'
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new OAuthRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleAuthorise);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = array(
            'params'=>array(
                'response_type'=>'code',
                'client_id'=>$this->sampleAuthorise['merchantId'],
                'scope'=>$this->sampleAuthorise['oAuthScope'],
                'redirect_uri'=>$this->sampleAuthorise['returnUrl'],
                'state' => $this->sampleAuthorise['transactionId'],
                'prefill'=>array('email'=>$this->sampleAuthorise['email'])
            ),
            'redirectURL'=>$this->request->getOAuthUrl().'/authorize'
        );
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleAuthorise['merchantId'], $this->request->getMerchantId());
        $this->assertSame($this->sampleAuthorise['oAuthScope'], $this->request->getOAuthScope());
        $this->assertSame($this->sampleAuthorise['returnUrl'], $this->request->getReturnUrl());
        $this->assertSame($this->sampleAuthorise['transactionId'], $this->request->getTransactionId());
        $this->assertSame($this->sampleAuthorise['email'], $this->request->getEmail());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls authorise create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleAuthorise).
        $result = $this->request->send($this->request->getData());
        $this->assertInstanceOf(OAuthResponse::class, $result);
    }

    // Assert the authorise create method is being handed the correct parameters
    public function authoriseCreate($data){

         $this->assertEquals(array("params"=>$this->request->getData()), $data);

        return $this->getMockBuilder(RedirectFlow::class)
                ->disableOriginalConstructor()
                ->getMock();
    }
}
