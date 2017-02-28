<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\GoCardlessV2\Message\CustomerBankAccountResponse;
use Omnipay\GoCardlessV2\Message\UpdateCustomerBankAccountRequest;
use Omnipay\Tests\TestCase;

class UpdateCustomerBankAccountRequestTest extends TestCase
{
    /**
     * @var UpdateCustomerBankAccountRequest
     */
    private $request;

    /**
     * @var array sample customerBankAccount data to drive test
     */
    private $sampleData = [
        'customerBankAccountId' => 'CU123123123',
        'customerBankAccountData' => ['Some extra data' => 'just as placeholders'],
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
                    'update',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('customerBankAccounts')
            ->will($this->returnValue($customerBankAccountService));
        $customerBankAccountService->expects($this->any())
            ->method('update')
            ->will($this->returnCallback([$this, 'customerBankAccountGet']));

        $this->request = new UpdateCustomerBankAccountRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = [
            'customerBankAccountData' => ['params' => $this->sampleData['customerBankAccountData']],
            'customerBankAccountId' => $this->sampleData['customerBankAccountId'],
        ];
        $this->assertSame($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['customerBankAccountId'], $this->request->getCustomerBankAccountId());
        $this->assertSame($this->sampleData['customerBankAccountData'], $this->request->getCustomerBankAccountData());
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send();
        $this->assertInstanceOf(CustomerBankAccountResponse::class, $result);
    }

    // Assert the customerBankAccount get method is being handed the customerBankAccountId
    public function customerBankAccountGet($id, $data)
    {
        $this->assertEquals($this->sampleData['customerBankAccountId'], $id);
        $this->assertEquals($this->request->getData()['customerBankAccountData'], $data);

        return $this->getMockBuilder(CustomerBankAccount::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
