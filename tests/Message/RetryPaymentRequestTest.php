<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Payment;
use GoCardlessPro\Services\PaymentsService;
use Omnipay\Tests\TestCase;

class RetryPaymentRequestTest extends TestCase
{
    /**
     * @var RetryPaymentRequest
     */
    private $request;

    /**
     * @var array fully populated sample payment data to drive test
     */
    private $sampleData = array(
        'paymentId' => 'CU123123123',
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
                'payments'
            ))
            ->getMock();
        $paymentService = $this->getMockBuilder(PaymentsService::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'retry'
            ))
            ->getMock();

        $gateway->expects($this->any())
            ->method('payments')
            ->will($this->returnValue($paymentService));
        $paymentService->expects($this->any())
            ->method('retry')
            ->will($this->returnCallback(array($this, 'paymentGet')));

        $this->request = new RetryPaymentRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = array('paymentData' => array('metadata'=>$this->sampleData['paymentMetaData']),
            'paymentId' => $this->sampleData['paymentId'],);
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['paymentId'], $this->request->getPaymentId());
        $this->assertSame($this->sampleData['paymentMetaData'], $this->request->getPaymentMetaData());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls payment create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->samplePayment).
        $result = $this->request->send($this->request->getData());
        $this->assertInstanceOf(PaymentResponse::class, $result);
    }

    // Assert the payment get method is being handed the paymentId
    public function paymentGet($data){

         $this->assertEquals($this->sampleData['paymentId'], $data);

        return $this->getMockBuilder(Payment::class)
                ->disableOriginalConstructor()
                ->getMock();
    }
}
