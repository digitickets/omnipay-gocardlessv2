<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\GoCardlessV2\Message\CreateCustomerBankAccountRequest;
use Omnipay\GoCardlessV2\Message\BankAccountResponse;
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
    private $sampleCustomerBankAccount = [
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

        $this->request = new CreateCustomerBankAccountRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCustomerBankAccount);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = $this->sampleCustomerBankAccount;
        $data['branch_code']=$data['bank_branch_code'];
        $data['country_code']=$data['bank_country_code'];
        $data['metadata']=$data['bankAccountMetaData'];
        $data['links']['customer'] = $this->sampleCustomerBankAccount['customerReference'];
        unset($data['customerReference'], $data['bankAccountMetaData'], $data['bank_country_code'], $data['bank_branch_code']);
        asort($data);
        $result =   $this->request->getData();
        asort($result['params']);
        $this->assertSame(['params' => $data], $result);
    }

    public function testRequestDataIsStoredCorrectly()
    {
        $this->assertNull($this->request->getBankAccountReference());
        foreach(['account_holder_name' => 'getAccountHolderName',
            'account_number' => 'getAccountNumber',
            'bank_code' => 'getBankCode',
            'bank_branch_code' => 'getBankBranchCode',
            'bank_country_code' => 'getBankCountryCode',
            'currency' => 'getCurrency',
            'iban' => 'getIban',
            'bankAccountMetaData' => 'getBankAccountMetaData'
            ] AS $data => $method){
            $this->assertSame($this->sampleCustomerBankAccount[$data], $this->request->{$method}());
        }

        $this->assertSame($this->sampleCustomerBankAccount['customerReference'], $this->request->getCustomerReference());
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
