<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\GoCardlessV2\Message\BankAccountResponse;
use Omnipay\GoCardlessV2\Message\FindCustomerBankAccountRequest;
use Omnipay\Tests\TestCase;

class FindCustomerBankAccountRequestTest extends TestCase
{
    /**
     * @var FindCustomerBankAccountRequest
     */
    private $request;

    /**
     * @var array fully populated sample customerBankAccount data to drive test
     */
    private $sampleData = [
        'bankAccountReference' => 'CU123123123',
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
                    'get',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customerBankAccounts')
            ->will($this->returnValue($customerBankAccountService));
        $customerBankAccountService->expects($this->any())
            ->method('get')
            ->will($this->returnCallback([$this, 'customerBankAccountGet']));

        $this->request = new FindCustomerBankAccountRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame([], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['bankAccountReference'], $this->request->getBankAccountReference());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send();
        $this->assertInstanceOf(BankAccountResponse::class, $result);
    }

    // Assert the customerBankAccount get method is being handed the customerBankAccountId
    public function customerBankAccountGet($data)
    {
        $this->assertEquals($this->sampleData['bankAccountReference'], $data);

        return $this->getMockBuilder(CustomerBankAccount::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
