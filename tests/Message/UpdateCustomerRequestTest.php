<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Customer;
use GoCardlessPro\Services\CustomersService;
use Omnipay\GoCardlessV2\Message\CustomerResponse;
use Omnipay\GoCardlessV2\Message\UpdateCustomerRequest;
use Omnipay\Tests\TestCase;

class UpdateCustomerRequestTest extends TestCase
{
    /**
     * @var UpdateCustomerRequest
     */
    private $request;

    /**
     * @var array sample customer data to drive test
     */
    private $sampleData = [
        'customerId' => 'CU123123123',
        'customerData' => ['Some extra data' => 'just as placeholders'],
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
                    'update',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customers')
            ->will($this->returnValue($customerService));
        $customerService->expects($this->any())
            ->method('update')
            ->will($this->returnCallback([$this, 'customerGet']));

        $this->request = new UpdateCustomerRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = [
            'customerData' => ['params' => $this->sampleData['customerData']],
            'customerId' => $this->sampleData['customerId'],
        ];
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['customerId'], $this->request->getCustomerId());
        $this->assertSame($this->sampleData['customerData'], $this->request->getCustomerData());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customer create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomer).
        $result = $this->request->send();
        $this->assertInstanceOf(CustomerResponse::class, $result);
    }

    // Assert the customer get method is being handed the customerId
    public function customerGet($id, $data)
    {
        $this->assertEquals($this->sampleData['customerId'], $id);
        $this->assertEquals($this->request->getData()['customerData'], $data);

        return $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
