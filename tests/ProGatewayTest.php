<?php

namespace Omnipay\GoCardlessV2Tests;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\GoCardlessV2\ProGateway;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\GoCardlessV2\Message;

/**
 * Class ProGatewayTest
 * This also tests the base abstract gateway.
 */
class ProGatewayTest extends GatewayTestCase
{
    /**
     * @var ProGateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ProGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    //-------------------- Test Pro Gateway Features -------------------#

    public function testCreateCustomer()
    {
        $request = $this->gateway->createCustomer();
        $this->assertInstanceOf(Message\CreateCustomerRequest::class, $request);
    }

    public function testCreateCustomerBankAccount()
    {
        $request = $this->gateway->createBankAccount();
        $this->assertInstanceOf(Message\CreateCustomerBankAccountRequest::class, $request);
    }

    public function testCreateMandate()
    {
        $request = $this->gateway->createMandate();
        $this->assertInstanceOf(Message\CreateMandateRequest::class, $request);
    }
}
