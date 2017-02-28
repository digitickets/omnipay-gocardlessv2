<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\Tests\TestCase;

class CreateCustomerBankAccountRequestFromTokenTest extends TestCase
{
    /**
     * @var CreateCustomerBankAccountRequest
     */
    private $request;

    /**
     * @var array fully populated sample customerBankAccount data to drive test
     */
    private $sampleCustomerBankAccount = array(
        'customerBankAccountToken' => 'TK123123123',
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

        $this->request = new CreateCustomerBankAccountRequestFromToken($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCustomerBankAccount);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data['links']['customer'] = $this->sampleCustomerBankAccount['customerId'];
        $data['links']['customer_bank_account_token'] = $this->sampleCustomerBankAccount['customerBankAccountToken'];
        $this->assertSame(array('params' => $data), $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertNull($this->request->getCustomerBankAccountId());
        $this->assertSame($this->sampleCustomerBankAccount['customerId'], $this->request->getCustomerId());
        $this->assertSame($this->sampleCustomerBankAccount['customerBankAccountToken'], $this->request->getCustomerBankAccountToken());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send();
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
