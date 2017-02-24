<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Refund;
use GoCardlessPro\Services\RefundsService;
use Omnipay\Tests\TestCase;

class UpdateRefundRequestTest extends TestCase
{
    /**
     * @var UpdateRefundRequest
     */
    private $request;

    /**
     * @var array fully populated sample refund data to drive test
     */
    private $sampleData = array(
        'transactionReference' => 'CU123123123',
        'paymentMetaData' => array(
            'meta1' => 'Lorem Ipsom Dolor Est',
            'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
            'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
        ),
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'refunds'
            ))
            ->getMock();
        $refundService = $this->getMockBuilder(RefundsService::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'update'
            ))
            ->getMock();

        $gateway->expects($this->any())
            ->method('refunds')
            ->will($this->returnValue($refundService));
        $refundService->expects($this->any())
            ->method('update')
            ->will($this->returnCallback(array($this, 'refundGet')));

        $this->request = new UpdateRefundRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = array('refundData' => array('metadata'=>$this->sampleData['paymentMetaData']),
            'refundId' => $this->sampleData['transactionReference'],);
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['transactionReference'], $this->request->getTransactionReference());
        $this->assertSame($this->sampleData['paymentMetaData'], $this->request->getPaymentMetaData());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls refund create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleRefund).
        $result = $this->request->sendData($this->request->getData());
        $this->assertInstanceOf(RefundResponse::class, $result);
    }

    // Assert the refund get method is being handed the refundId
    public function refundGet($id, $data){

        $this->assertEquals($this->sampleData['transactionReference'], $id);
        $this->assertEquals($this->request->getData()['refundData'], $data);

        return $this->getMockBuilder(Refund::class)
                ->disableOriginalConstructor()
                ->getMock();
    }
}
