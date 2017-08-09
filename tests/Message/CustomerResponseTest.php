<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreateCustomerRequest;
use Omnipay\GoCardlessV2\Message\CustomerResponse;
use Omnipay\Tests\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class CustomerResponseTest extends TestCase
{
    /**
     * @var CreateCustomerRequest|PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreateCustomerRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCustomerData()
    {
        $data = json_decode('{"id":"CU1234"}');

        $response = new CustomerResponse($this->request, $data);
        $this->assertEquals($data, $response->getCustomerData());
        $this->assertEquals('CU1234', $response->getCustomerReference());
    }

    public function testFailedCustomerData()
    {
        $data = null;

        $response = new CustomerResponse($this->request, $data);
        $this->assertNull($response->getCustomerData());
    }

    public function testSuccessful()
    {
        $data = json_decode('{"id":"CU1234"}');
        $response = new CustomerResponse($this->request, $data);
        $this->assertTrue($response->isSuccessful());
    }
}
