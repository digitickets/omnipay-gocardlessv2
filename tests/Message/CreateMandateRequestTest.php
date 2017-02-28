<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Mandate;
use GoCardlessPro\Services\MandatesService;
use Omnipay\Tests\TestCase;

class CreateMandateRequestTest extends TestCase
{
    /**
     * @var CreateMandateRequest
     */
    private $request;

    /**
     * @var array fully populated sample mandate data to drive test
     */
    private $sampleMandate = array(
        'mandateData' => array(
            'reference' => 'TestRef',
            'scheme' => 'bacs',
            'metadata' => array(
                'meta1' => 'Lorem Ipsom Dolor Est',
                'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
                'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
            ),
        ),
        'customerBankAccountId' => 'CB1231235413',
        'creditorId' => 'CR783472',
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
                    'create',
                )
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('mandates')
            ->will($this->returnValue($mandateService));
        $mandateService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback(array($this, 'mandateCreate')));

        $this->request = new CreateMandateRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleMandate);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = $this->sampleMandate['mandateData'];
        $data['links']['customer_bank_account'] = $this->sampleMandate['customerBankAccountId'];
        $data['links']['creditor'] = $this->sampleMandate['creditorId'];
        $this->assertSame(array('params' => $data), $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertNull($this->request->getMandateId());
        $this->assertSame($this->sampleMandate['mandateData'], $this->request->getMandateData());
        $this->assertSame($this->sampleMandate['customerBankAccountId'], $this->request->getCustomerBankAccountId());
        $this->assertSame($this->sampleMandate['creditorId'], $this->request->getCreditorId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls mandate create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleMandate).
        $result = $this->request->send();
        $this->assertInstanceOf(MandateResponse::class, $result);
    }

    // Assert the mandate create method is being handed the correct parameters
    public function mandateCreate($data)
    {
        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(Mandate::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
