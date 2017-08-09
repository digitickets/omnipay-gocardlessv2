<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Customer;
use GoCardlessPro\Services\CustomersService;
use Omnipay\Common\CreditCard;
use Omnipay\GoCardlessV2\Message\CreateCustomerRequest;
use Omnipay\GoCardlessV2\Message\CustomerResponse;
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
    private $sampleCustomer = [];

    public function setUp()
    {
        $this->sampleCustomer = [
            'card' => new CreditCard(
                [
                    'firstName' => 'Mike',
                    'lastName' => 'Jones',
                    'email' => 'mike.jones@example.com',
                    'address1' => 'Iconic Song House, 47 Penny Lane',
                    'address2' => 'Wavertree',
                    'city' => 'Liverpool',
                    'company' => 'Mike Jones Enterprises',
                    'country' => 'GB',
                    'postal_code' => 'L18 1DE',
                    'state' => 'Merseyside',
                ]
            ),
            'customerMetaData' => [
                'meta1' => 'Lorem Ipsom Dolor Est',
                'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
                'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
            ],
            'swedishIdentityNumber' => '123',
        ];

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
                    'create',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customers')
            ->will($this->returnValue($customerService));
        $customerService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback([$this, 'customerCreate']));

        $this->request = new CreateCustomerRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCustomer);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $targetParams = [
            'email' => $this->sampleCustomer['card']->getEmail(),
            'given_name' => $this->sampleCustomer['card']->getFirstName(),
            'family_name' => $this->sampleCustomer['card']->getLastName(),
            'country_code' => $this->sampleCustomer['card']->getCountry(),
            'metadata' => $this->sampleCustomer['customerMetaData'],
            'address_line1' => $this->sampleCustomer['card']->getAddress1(),
            'address_line2' => $this->sampleCustomer['card']->getAddress2(),
            'city' => $this->sampleCustomer['card']->getCity(),
            'company_name' => $this->sampleCustomer['card']->getCompany(),
            'postal_code' => $this->sampleCustomer['card']->getPostcode(),
            'region' => $this->sampleCustomer['card']->getState(),
            'swedish_identity_number' => $this->sampleCustomer['swedishIdentityNumber'],
        ];

        $this->assertSame(['params' => $targetParams], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertNull($this->request->getCustomerReference());
        $this->assertSame($this->sampleCustomer['card'], $this->request->getCard());
        $this->assertSame($this->sampleCustomer['customerMetaData'], $this->request->getCustomerMetaData());
        $this->assertSame($this->sampleCustomer['swedishIdentityNumber'], $this->request->getSwedishIdentityNumber());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customer create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomer).
        $result = $this->request->send();
        $this->assertInstanceOf(CustomerResponse::class, $result);
    }

    // Assert the customer create method is being handed the correct parameters
    public function customerCreate($data)
    {
        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
