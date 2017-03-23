<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\GoCardlessV2\Message\CreateCustomerBankAccountRequestFromToken;
use Omnipay\GoCardlessV2\Message\BankAccountResponse;
use Omnipay\Tests\TestCase;

class CreateCustomerBankAccountRequestFromTokenTest extends TestCase
{
    /**
     * @var CreateCustomerBankAccountRequestFromToken
     */
    private $request;

    /**
     * @var array fully populated sample customerBankAccount data to drive test
     */
    private $sampleCustomerBankAccount = [
        'customerBankAccountToken' => 'TK123123123',
        'customerReference' => 'CU1231235413',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'customerBankAccounts',
                ]
            )
            ->getMock();
        $customerBankAccountService = $this->getMockBuilder(CustomerBankAccountsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'create',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customerBankAccounts')
            ->will($this->returnValue($customerBankAccountService));
        $customerBankAccountService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback([$this, 'customerBankAccountCreate']));

        $this->request = new CreateCustomerBankAccountRequestFromToken($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCustomerBankAccount);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data['links']['customer'] = $this->sampleCustomerBankAccount['customerReference'];
        $data['links']['customer_bank_account_token'] = $this->sampleCustomerBankAccount['customerBankAccountToken'];
        $this->assertSame(['params' => $data], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertNull($this->request->getBankAccountReference());
        $this->assertSame($this->sampleCustomerBankAccount['customerReference'], $this->request->getCustomerReference());
        $this->assertSame($this->sampleCustomerBankAccount['customerBankAccountToken'], $this->request->getCustomerBankAccountToken());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send();
        $this->assertInstanceOf(BankAccountResponse::class, $result);
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
