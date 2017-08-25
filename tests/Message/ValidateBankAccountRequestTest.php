<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\CustomerBankAccount;
use GoCardlessPro\Services\CustomerBankAccountsService;
use Omnipay\GoCardlessV2\Message\BankDetailsResponse;
use Omnipay\GoCardlessV2\Message\CreateCustomerBankAccountRequestFromToken;
use Omnipay\GoCardlessV2\Message\BankAccountResponse;
use Omnipay\GoCardlessV2\Message\ValidateBankAccountRequest;
use Omnipay\Tests\TestCase;

class ValidateBankAccountRequestTest extends TestCase
{
    /**
     * @var ValidateBankAccountRequest
     */
    private $request;

    /**
     * @var array fully populated sample customerBankAccount data to drive test
     */
    private $sampleCustomerBankAccount = [
        'account_holder_name' => "J Doe",
        'account_number' => "44779911",
        'bank_code' => "123",
        'bank_branch_code' => "456",
        'bank_country_code' => "GB",
        'currency' => "GBP",
        'iban' => "GB0012345644779911",
        'bank_account_metadata' => ["ref"=>"fer"],
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'bankDetailsLookups',
                ]
            )
            ->getMock();
        $BankDetailsLookupService = $this->getMockBuilder(CustomerBankAccountsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'create',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('bankDetailsLookups')
            ->will($this->returnValue($BankDetailsLookupService));
        $BankDetailsLookupService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback([$this, 'bankDetailsLookupCreate']));

        $this->request = new ValidateBankAccountRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleCustomerBankAccount);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = $this->sampleCustomerBankAccount;
        $data['metadata']=$data['bank_account_metadata'];
        $data['branch_code']=$data['bank_branch_code'];
        $data['country_code']=$data['bank_country_code'];
        unset($data['bank_account_metadata'], $data['bank_branch_code'], $data['bank_country_code']);
        $result = $this->request->getData();
        ksort($data);
        ksort($result);
        $this->assertSame(['params' => $data], $result);
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls customerBankAccount create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleCustomerBankAccount).
        $result = $this->request->send();
        $this->assertInstanceOf(BankDetailsResponse::class, $result);
    }

    // Assert the customerBankAccount create method is being handed the correct parameters
    public function bankDetailsLookupCreate($data)
    {
        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(CustomerBankAccount::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
