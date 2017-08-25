<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\CreatePaymentRequest;
use Omnipay\GoCardlessV2\Message\PaymentResponse;
use Omnipay\Tests\TestCase;

class AbstractResponseTest extends TestCase
{
    /**
     * @var CreatePaymentRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreatePaymentRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetMetaData()
    {
        $array = [
            'id' => 'P123',
            'amount' => '1000',
            'amount_refunded' => '100',
            'created_at' => '2017-01-01T12:01:02.345678',
            'charge_date' => '2017-01-01T12:01:02.345678',
            'currency' => 'GBP',
            'reference' => 'Some Reference',
            'metadata' => ['meta1' => '1atem'],
        ];
        $raw = json_encode($array);
        $data = json_decode($raw);

        $response = new PaymentResponse($this->request, $data);

        $this->assertEquals($array['metadata']['meta1'], $response->getMetaField('meta1'));
        $this->assertEquals(null, $response->getMetaField('meta2'));
    }

    public function testGetLinkData()
    {
        $array = [
            'id' => 'P123',
            'amount' => '1000',
            'amount_refunded' => '100',
            'created_at' => '2017-01-01T12:01:02.345678',
            'charge_date' => '2017-01-01T12:01:02.345678',
            'currency' => 'GBP',
            'reference' => 'Some Reference',
            'links' => ['mandate' => 'etadnam'],
        ];
        $raw = json_encode($array);
        $data = json_decode($raw);

        $response = new PaymentResponse($this->request, $data);

        $this->assertEquals($array['links']['mandate'], $response->getLinkField('mandate'));
        $this->assertEquals(null, $response->getLinkField('customer'));
    }

}
