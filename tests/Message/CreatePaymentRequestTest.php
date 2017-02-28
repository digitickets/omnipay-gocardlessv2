<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Payment;
use GoCardlessPro\Services\PaymentsService;
use Omnipay\Tests\TestCase;

class CreatePaymentRequestTest extends TestCase
{
    /**
     * @var CreatePaymentRequest
     */
    private $request;

    /**
     * @var array fully populated sample payment data to drive test
     */
    private $samplePayment = array(
        'amount' => 12.99,
        'paymentDescription' => 'bacs payment',
        'serviceFeeAmount' => '1.23',
        'paymentMetaData' => array(
            'meta1' => 'Lorem Ipsom Dolor Est',
            'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
            'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
        ),
        'paymentDate' => '2017-01-01',
        'currency' => 'GBP',
        'reference' => '13wekjhewe123n3hjd8',
        'mandateId' => 'CB1231235413',
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'payments',
                )
            )
            ->getMock();
        $paymentService = $this->getMockBuilder(PaymentsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'create',
                )
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('payments')
            ->will($this->returnValue($paymentService));
        $paymentService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback(array($this, 'paymentCreate')));

        $this->request = new CreatePaymentRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->samplePayment);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = array(
            "params" => array(
                'amount' => $this->samplePayment['amount'] * 100,
                'description' => $this->samplePayment['paymentDescription'],
                'app_fee' => $this->samplePayment['serviceFeeAmount'],
                'metadata' => $this->samplePayment['paymentMetaData'],
                'charge_date' => $this->samplePayment['paymentDate'],
                'currency' => $this->samplePayment['currency'],
                'reference' => $this->samplePayment['reference'],
                'links' => array('mandate' => $this->samplePayment['mandateId']),
            ),
        );

        $this->assertEquals($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        foreach ($this->samplePayment as $field => $value) {
            $function = 'get'.ucfirst($field);
            $this->assertEquals($value, $this->request->{$function}());
        }
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls payment create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->samplePayment).
        $result = $this->request->send();
        $this->assertInstanceOf(PaymentResponse::class, $result);
    }

    // Assert the payment create method is being handed the correct parameters
    public function paymentCreate($data)
    {

        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
