<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Refund;
use GoCardlessPro\Services\RefundsService;
use Omnipay\GoCardlessV2\Message\CreateRefundRequest;
use Omnipay\GoCardlessV2\Message\RefundResponse;
use Omnipay\Tests\TestCase;

class CreateRefundRequestTest extends TestCase
{
    /**
     * @var CreateRefundRequest
     */
    private $request;

    /**
     * @var array fully populated sample refund data to drive test
     */
    private $sampleRefund = [
        'amount' => 12.99,
        'totalRefundedAmount' => 16.32,
        'paymentMetaData' => [
            'meta1' => 'Lorem Ipsom Dolor Est',
            'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
            'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
        ],
        'reference' => '13wekjhewe123n3hjd8',
        'transactionReference' => 'PR1328317',
    ];

    public function setUp()
    {
        $gateway = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'refunds',
                ]
            )
            ->getMock();
        $refundService = $this->getMockBuilder(RefundsService::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'create',
                ]
            )
            ->getMock();

        $gateway->expects($this->any())
            ->method('refunds')
            ->will($this->returnValue($refundService));
        $refundService->expects($this->any())
            ->method('create')
            ->will($this->returnCallback([$this, 'refundCreate']));

        $this->request = new CreateRefundRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleRefund);
    }

    public function testGetDataReturnsCorrectArray()
    {
        $data = [
            'params' => [
                'links' => ['payment' => $this->sampleRefund['transactionReference']],
                'amount' => $this->sampleRefund['amount'] * 100,
                'total_amount_confirmation' => $this->sampleRefund['totalRefundedAmount'],
                'reference' => $this->sampleRefund['reference'],
                'metadata' => $this->sampleRefund['paymentMetaData'],
            ],
        ];

        $this->assertEquals($data, $this->request->getData());
    }

    public function testRequestDataIsStoredCorrectly()
    {
        foreach ($this->sampleRefund as $field => $value) {
            $function = 'get'.ucfirst($field);
            $this->assertEquals($value, $this->request->{$function}());
        }
    }

    public function testSendDataReturnsCorrectType()
    {
        // this will trigger additional validation as the sendData method calls refund create that validates the parameters handed to it match
        // the original data handed in to the initialise (in $this->sampleRefund).
        $result = $this->request->send();
        $this->assertInstanceOf(RefundResponse::class, $result);
    }

    // Assert the refund create method is being handed the correct parameters
    public function refundCreate($data)
    {
        $this->assertEquals($this->request->getData(), $data);

        return $this->getMockBuilder(Refund::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
