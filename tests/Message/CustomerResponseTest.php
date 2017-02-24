<?php
namespace Omnipay\GoCardlessV2\Message;
use Omnipay\Tests\TestCase;

class CustomerResponseTest extends TestCase
{
    /**
     * @var CreateCustomerRequest
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
        $data = 'customerData';

        $response = new CustomerResponse($this->request, $data);
        $this->assertEquals('customerData', $response->getCustomerData());
    }

    public function testFailedCustomerData()
    {
        $data = null;

        $response = new CustomerResponse($this->request, $data);
        $this->assertNull($response->getCustomerData());
    }

    public function testSuccessful(){
        $data = 'customerData';
        $response = new CustomerResponse($this->request, $data);
        $this->assertTrue($response->isSuccessful());
    }

}
