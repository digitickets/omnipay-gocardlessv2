<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\BankAccountResponse;
use Omnipay\GoCardlessV2\Message\FindCustomerBankAccountRequest;
use Omnipay\Tests\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class BankAccountResponseTest extends TestCase
{
    /**
     * @var FindCustomerBankAccountRequest|PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(FindCustomerBankAccountRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCustomerData()
    {
        $data = json_decode('{"id": "BA123",
    "created_at": "2014-05-27T12:43:17.000Z",
    "account_holder_name": "Nude Wines",
    "account_number_ending": "11",
    "country_code": "GB",
    "currency": "GBP",
    "bank_name": "BARCLAYS BANK PLC",
    "enabled": true,
    "links": {
      "creditor": "CR123"
    }}');

        $response = new BankAccountResponse($this->request, $data);
        $this->assertEquals($data, $response->getData());
        $this->assertEquals("Nude Wines", $response->getBankAccountHolder());
        $this->assertEquals("BARCLAYS BANK PLC", $response->getBankName());
        $this->assertEquals("11", $response->getBankAccountNumberEnding());
        $this->assertTrue($response->isSuccessful());
    }
}
