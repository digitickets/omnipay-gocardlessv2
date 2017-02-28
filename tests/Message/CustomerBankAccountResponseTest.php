<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreateCustomerBankAccountRequest;
use Omnipay\GoCardlessV2\Message\CustomerBankAccountResponse;
use Omnipay\Tests\TestCase;

class CustomerBankAccountResponseTest extends TestCase
{
    /**
     * @var CreateCustomerBankAccountRequest|\Mockery\MockInterface
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreateCustomerBankAccountRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCustomerBankAccountData()
    {
        $data = 'customerBankAccountData';

        $response = new CustomerBankAccountResponse($this->request, $data);
        $this->assertEquals('customerBankAccountData', $response->getCustomerBankAccountData());
    }

    public function testFailedCustomerBankAccountData()
    {
        $data = null;

        $response = new CustomerBankAccountResponse($this->request, $data);
        $this->assertNull($response->getCustomerBankAccountData());
    }
}
