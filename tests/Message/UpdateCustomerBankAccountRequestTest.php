<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\GoCardlessV2\Message\BankAccountResponse;
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
        'bankAccountReference' => 'CU123123123',
        'account_holder_name' => 'Example User',
        'account_number' => 'League',
        'bank_code' => '123 Billing St',
        'bank_branch_code' => 'Billsville',
        'bank_country_code' => 'Billstown',
        'currency' => '12345',
        'iban' => 'CA',
        'bankAccountMetaData' => [
            'billingPhone' => '(555) 123-4567',
            'shippingAddress1' => '123 Shipping St',
            'shippingAddress2' => 'Shipsville',
        ],
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
        $data = $this->sampleData;
        $data['branch_code'] = $data['bank_branch_code'];
        $data['country_code'] = $data['bank_country_code'];
        $data['metadata'] = $data['bankAccountMetaData'];
        unset($data['bankAccountMetaData'], $data['bank_country_code'], $data['bank_branch_code'], $data['bankAccountReference']);
        asort($data);

        $data = [
            'customerBankAccountData' => ['params' => $data],
            'customerBankAccountId' => $this->sampleData['bankAccountReference'],
        ];


        $result = $this->request->getData();
        asort($result['customerBankAccountData']['params']);

        $this->assertSame($data, $result);
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertSame($this->sampleData['bankAccountReference'], $this->request->getBankAccountReference());
        foreach ([
                     'account_holder_name' => 'getAccountHolderName',
                     'account_number' => 'getAccountNumber',
                     'bank_code' => 'getBankCode',
                     'bank_branch_code' => 'getBankBranchCode',
                     'bank_country_code' => 'getBankCountryCode',
                     'currency' => 'getCurrency',
                     'iban' => 'getIban',
                     'bankAccountMetaData' => 'getBankAccountMetaData',
                 ] AS $data => $method) {
            $this->assertSame($this->sampleData[$data], $this->request->{$method}());
        }
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send();
        $this->assertInstanceOf(BankAccountResponse::class, $result);
    }

    // Assert the customerBankAccount get method is being handed the bankAccountReference
    public function customerBankAccountGet($id, $data)
    {
        $this->assertEquals($this->sampleData['bankAccountReference'], $id);
        $this->assertEquals($this->request->getData()['customerBankAccountData'], $data);

        return $this->getMockBuilder(CustomerBankAccount::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
