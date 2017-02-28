<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\GoCardlessV2\Message\CustomerBankAccountResponse;
use Omnipay\GoCardlessV2\Message\DisableCustomerBankAccountRequest;
use Omnipay\Tests\TestCase;

class DisableCustomerBankAccountRequestTest extends TestCase
{
    /**
     * @var DisableCustomerBankAccountRequest
     */
    private $request;

    /**
     * @var array fully populated sample customerBankAccount data to drive test
     */
    private $sampleData = [
        'customerBankAccountId' => 'CU123123123',
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
                    'disable',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customerBankAccounts')
            ->will($this->returnValue($customerBankAccountService));
        $customerBankAccountService->expects($this->any())
            ->method('disable')
            ->will($this->returnCallback([$this, 'customerBankAccountGet']));

        $this->request = new DisableCustomerBankAccountRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        // this should be blank
        $this->assertSame([], $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['customerBankAccountId'], $this->request->getCustomerBankAccountId());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send();
        $this->assertInstanceOf(CustomerBankAccountResponse::class, $result);
    }

    // Assert the customerBankAccount get method is being handed the customerBankAccountId
    public function customerBankAccountGet($data)
    {
        $this->assertEquals($this->sampleData['customerBankAccountId'], $data);

        return $this->getMockBuilder(CustomerBankAccount::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
