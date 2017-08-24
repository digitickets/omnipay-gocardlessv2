<?php


namespace Omnipay\GoCardlessV2Tests\Message;


use GoCardlessPro\Client;
use GoCardlessPro\Core\Paginator;
use GoCardlessPro\Services\PaymentsService;
use Omnipay\GoCardlessV2\Message\FindPaymentsByCustomerRequest;
use Omnipay\GoCardlessV2\Message\PaymentSearchResponse;
use Omnipay\Tests\TestCase;

class FindPaymentsByCustomerRequestNoParamsTest extends TestCase
{
    /**
     * @var FindPaymentsByCustomerRequest
     */
    private $request;


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

    }


    public function testSendDataReturnsCorrectType()
    {
        $this->setExpectedException(\Exception::class);
        $this->request->initialize()->getData();
    }
}
