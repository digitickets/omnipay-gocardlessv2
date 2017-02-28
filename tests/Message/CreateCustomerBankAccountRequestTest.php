<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\Tests\TestCase;

class CreateCustomerBankAccountRequestTest extends TestCase
{
    /**
     * @var CreateCustomerBankAccountRequest
     */
    private $request;

    /**
     * @var array fully populated sample customerBankAccount data to drive test
     */
    private $sampleCustomerBankAccount = array(
        'customerBankAccountData' => array(
            'account_holder_name' => 'Example User',
            'account_number' => 'League',
            'bank_code' => '123 Billing St',
            'branch_code' => 'Billsville',
            'country_code' => 'Billstown',
            'currency' => '12345',
            'iban' => 'CA',
            'metadata' => array(
                'billingPhone' => '(555) 123-4567',
                'shippingAddress1' => '123 Shipping St',
                'shippingAddress2' => 'Shipsville',
            ),
        ),
        'customerId' => 'CU1231235413',
    );

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'customerBankAccounts',
                )
            )
            ->getMock();
        $customerBankAccountService = $this->getMockBuilder(CustomerBankAccountsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'create',
                )
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customerBankAccounts')
            ->will($this->returnValue($customerBankAccountService));
        $customerBankAccountService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback(array($this, 'customerBankAccountCreate')));

        $this->request = new CreateCustomerBankAccountRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCustomerBankAccount);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = $this->sampleCustomerBankAccount['customerBankAccountData'];
        $data['links']['customer'] = $this->sampleCustomerBankAccount['customerId'];
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertNull($this->request->getCustomerBankAccountId());
        $this->assertSame($this->sampleCustomerBankAccount['customerBankAccountData'], $this->request->getCustomerBankAccountData());
        $this->assertSame($this->sampleCustomerBankAccount['customerId'], $this->request->getCustomerId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send($this->request->getData());
        $this->assertInstanceOf(CustomerBankAccountResponse::class, $result);
    }

    // Assert the customerBankAccount create method is being handed the correct parameters
    public function customerBankAccountCreate($data)
    {

        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(CustomerBankAccount::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
