<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Core\Paginator;
use GoCardlessPro\Services\PaymentsService;
use Omnipay\GoCardlessV2\Message\FindPaymentsByCustomerRequest;
use Omnipay\GoCardlessV2\Message\PaymentResponse;
use Omnipay\GoCardlessV2\Message\PaymentSearchResponse;
use Omnipay\Tests\TestCase;

class PaymentSearchResponseTest extends TestCase
{
    /**
     * @var FindPaymentsByCustomerRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(FindPaymentsByCustomerRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetPaymentData()
    {
        $service = $this->getMockBuilder(PaymentsService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $paginator = $this->getMockBuilder(Paginator::class)
            ->setConstructorArgs([$service])
            ->getMock();

        $response = new PaymentSearchResponse($this->request, $paginator);
        $this->assertEquals($paginator, $response->getData());

        $this->assertInstanceOf(PaymentResponse::class, $response->current());
        $this->assertEquals(null, $response->next());
        $this->assertEquals(null, $response->rewind());
        $this->assertEquals(null, $response->key());
        $this->assertEquals(false, $response->valid());
        $this->assertEquals(true, $response->isSuccessful());
    }
}
