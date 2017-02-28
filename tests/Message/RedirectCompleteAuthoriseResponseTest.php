<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Omnipay\GoCardlessV2\Message\RedirectCompleteAuthoriseRequest;
use Omnipay\GoCardlessV2\Message\RedirectCompleteAuthoriseResponse;
use Omnipay\Tests\TestCase;

class RedirectCompleteAuthoriseResponseTest extends TestCase
{
    /**
     * @var RedirectCompleteAuthoriseRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(RedirectCompleteAuthoriseRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCompleteAuthoriseData()
    {
        $data = json_decode('{"links":{"mandate":"mandateVal","customer":"customerVal"}}');

        $response = new RedirectCompleteAuthoriseResponse($this->request, $data);
        $this->assertEquals('mandateVal', $response->getMandateId());
        $this->assertEquals('customerVal', $response->getCustomerId());
    }

    public function testFailedCompleteAuthoriseData()
    {
        $data = null;

        $response = new RedirectCompleteAuthoriseResponse($this->request, $data);
        $this->assertNull($response->getMandateId());
        $this->assertNull($response->getCustomerId());
    }
}
