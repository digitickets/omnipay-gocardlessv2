<?php
namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Tests\TestCase;

class MandateResponseTest extends TestCase
{
    /**
     * @var CreateMandateRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(CreateMandateRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetMandateData()
    {
        $data = 'mandateData';

        $response = new MandateResponse($this->request, $data);
        $this->assertEquals('mandateData', $response->getMandateData());
    }

    public function testFailedMandateData()
    {
        $data = null;

        $response = new MandateResponse($this->request, $data);
        $this->assertNull($response->getMandateData());
    }

}
