<?php


namespace Omnipay\GoCardlessV2Tests\Message;


use GoCardlessPro\Client;
use GoCardlessPro\Core\Paginator;
use GoCardlessPro\Services\PaymentsService;
use Omnipay\GoCardlessV2\Message\FindPaymentsByCustomerRequest;
use Omnipay\GoCardlessV2\Message\PaymentSearchResponse;
use Omnipay\Tests\TestCase;

class FindPaymentsByCustomerRequestTest extends TestCase
{
    /**
     * @var FindPaymentsByCustomerRequest
     */
    private $request;

    /**
     * @var array fully populated sample payment data to drive test
     */
    private $sampleData = [
        'customerReference' => 'CU123123123',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'payments',
                ]
            )
            ->getMock();
        $paymentService = $this->getMockBuilder(PaymentsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'all',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('payments')
            ->will($this->returnValue($paymentService));
        $paymentService->expects($this->any())
            ->method('all')
            ->will($this->returnCallback([$this, 'paymentAll']));

        $this->request = new FindPaymentsByCustomerRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame(['customer' => $this->sampleData['customerReference']], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['customerReference'], $this->request->getCustomerReference());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls payment create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->samplePayment).
        $result = $this->request->send();
        $this->assertInstanceOf(PaymentSearchResponse::class, $result);
    }

    // Assert the payment get method is being handed the paymentId
    public function paymentAll($data)
    {
        $this->assertEquals(["params" => ['customer' => $this->sampleData['customerReference']]], $data);

        return $this->getMockBuilder(Paginator::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

}
