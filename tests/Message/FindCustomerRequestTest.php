<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Customer;
use GoCardlessPro\Services\CustomersService;
use Omnipay\GoCardlessV2\Message\CustomerResponse;
use Omnipay\GoCardlessV2\Message\FindCustomerRequest;
use Omnipay\Tests\TestCase;

class FindCustomerRequestTest extends TestCase
{
    /**
     * @var FindCustomerRequest
     */
    private $request;

    /**
     * @var array fully populated sample customer data to drive test
     */
    private $sampleData = [
        'customerId' => 'CU123123123',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'customers',
                ]
            )
            ->getMock();
        $customerService = $this->getMockBuilder(CustomersService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'get',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customers')
            ->will($this->returnValue($customerService));
        $customerService->expects($this->any())
            ->method('get')
            ->will($this->returnCallback([$this, 'customerGet']));

        $this->request = new FindCustomerRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame([], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['customerId'], $this->request->getCustomerId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customer create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomer).
        $result = $this->request->send();
        $this->assertInstanceOf(CustomerResponse::class, $result);
    }

    // Assert the customer get method is being handed the customerId
    public function customerGet($data)
    {
        $this->assertEquals($this->sampleData['customerId'], $data);

        return $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
