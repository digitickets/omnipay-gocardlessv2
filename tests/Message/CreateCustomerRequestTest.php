<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Customer;
use GoCardlessPro\Services\CustomersService;
use Omnipay\Tests\TestCase;

class CreateCustomerRequestTest extends TestCase
{
    /**
     * @var CreateCustomerRequest
     */
    private $request;

    /**
     * @var array fully populated sample customer data to drive test
     */
    private $sampleCustomer = array(
        'customerData' => array(
            'given_name' => 'Mike',
            'family_name' => 'Jones',
            'email' => 'mike.jones@example.com',
            'address_line1' => 'Iconic Song House',
            'address_line2' => '47 Penny Lane',
            'address_line3' => 'Wavertree',
            'city' => 'Liverpool',
            'company_name' => 'Mike Jones Enterprises',
            'country_code' => 'GB',
            'language' => 'en',
            'metadata' => array(
                'meta1' => 'Lorem Ipsom Dolor Est',
                'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
                'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
            ),
            'postal_code' => 'L18 1DE',
            'region' => 'Merseyside',
            'swedish_identity_number' => '123',

        ),
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'customers'
            ))
            ->getMock();
        $customerService = $this->getMockBuilder(CustomersService::class)
            ->disableOriginalConstructor()
            ->setMethods(array(
                'create'
            ))
            ->getMock();

        $gateway->expects($this->any())
            ->method('customers')
            ->will($this->returnValue($customerService));
        $customerService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback(array($this, 'customerCreate')));

        $this->request = new CreateCustomerRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCustomer);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $this->assertSame($this->sampleCustomer['customerData'], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertNull($this->request->getCustomerId());
        $this->assertSame($this->sampleCustomer['customerData'], $this->request->getCustomerData());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customer create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomer).
        $result = $this->request->send($this->request->getData());
        $this->assertInstanceOf(CustomerResponse::class, $result);
    }

    // Assert the customer create method is being handed the correct parameters
    public function customerCreate($data){

         $this->assertEquals($this->sampleCustomer['customerData'], $data);

        return $this->getMockBuilder(Customer::class)
                ->disableOriginalConstructor()
                ->getMock();
    }
}
