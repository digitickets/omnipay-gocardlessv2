<?php
namespace Omnipay\GoCardlessV2\Message;
use Omnipay\Tests\TestCase;

class CustomerBankAccountResponseTest extends TestCase
{
    /**
     * @var CreateCustomerBankAccountRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreateCustomerBankAccountRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCustomerBankAccountData()
    {
        $data = 'customerBankAccountData';

        $response = new CustomerBankAccountResponse($this->request, $data);
        $this->assertEquals('customerBankAccountData', $response->getCustomerBankAccountData());
    }

    public function testFailedCustomerBankAccountData()
    {
        $data = null;

        $response = new CustomerBankAccountResponse($this->request, $data);
        $this->assertNull($response->getCustomerBankAccountData());
    }

}
