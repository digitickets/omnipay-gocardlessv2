<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\BankDetailsResponse;
use Omnipay\GoCardlessV2\Message\ValidateBankAccountRequest;
use Omnipay\Tests\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class BankDetailsResponseTest extends TestCase
{
    /**
     * @var ValidateBankAccountRequest|PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(ValidateBankAccountRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCustomerData()
    {
        $data = json_decode('{"id":"CU1234"}');

        $response = new BankDetailsResponse($this->request, $data);
        $this->assertEquals($data, $response->getBankDetailsData());
    }

    public function testFailedCustomerData()
    {
        $data = null;

        $response = new BankDetailsResponse($this->request, $data);
        $this->assertNull($response->getBankDetailsData());
    }

    public function testSuccessful()
    {
        $data = json_decode('{"id":"CU1234"}');
        $response = new BankDetailsResponse($this->request, $data);
        $this->assertTrue($response->isSuccessful());
    }
}
