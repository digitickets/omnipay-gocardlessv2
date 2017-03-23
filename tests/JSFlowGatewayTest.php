<?php

namespace Omnipay\GoCardlessV2Tests;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\GoCardlessV2\JSFlowGateway;
use Omnipay\GoCardlessV2\ProGateway;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\GoCardlessV2\Message;

/**
 * Class ProGatewayTest
 * This also tests the base abstract gateway.
 */
class JSFlowGatewayTest extends GatewayTestCase
{
    /**
     * @var ProGateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new JSFlowGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testCreateCustomer()
    {
        $request = $this->gateway->createCustomer();
        $this->assertInstanceOf(Message\CreateCustomerRequest::class, $request);
    }
    public function testCreateCustomerBankAccountFromToken()
    {
        $request = $this->gateway->createBankAccount();
        $this->assertInstanceOf(Message\CreateCustomerBankAccountRequestFromToken::class, $request);
    }

    public function testCreateMandate()
    {
        $request = $this->gateway->createMandate();
        $this->assertInstanceOf(Message\CreateMandateRequest::class, $request);
    }
}
