<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreateCustomerBankAccountRequest;
use Omnipay\GoCardlessV2\Message\BankAccountResponse;
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
        $data = json_decode('{"id":"BA1234"}');

        $response = new BankAccountResponse($this->request, $data);
        $this->assertEquals($data, $response->getBankAccountData());;
        $this->assertEquals("BA1234", $response->getBankAccountReference());
    }

    public function testFailedCustomerBankAccountData()
    {
        $data = null;

        $response = new BankAccountResponse($this->request, $data);
        $this->assertNull($response->getBankAccountData());
    }
}
