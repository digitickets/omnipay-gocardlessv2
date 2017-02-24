<?php

namespace Omnipay\GoCardlessV2\Message;

use Mockery;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\GoCardlessV2\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    /**
     * @dataProvider provideKeepsData
     * @param  string $field
     * @param  string $value
     */
    public function testKeepsData($field, $value)
    {
        $field = ucfirst($field);
        $this->assertSame($this->request, $this->request->{"set$field"}($value));
        $this->assertSame($value, $this->request->{"get$field"}());
    }

    public function provideKeepsData()
    {
        return array(
            array('oAuthSecret', 'abc123'),
        );
    }

    public function testCustomerBankAccountData()
    {
        $card = array(
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
        );

        $this->request->setCustomerBankAccountData($card);
        $data = $this->request->getCustomerBankAccountData();
        foreach($card as $field=>$value){
            $this->assertSame($value, $data[$field]);
        }

    }
}
