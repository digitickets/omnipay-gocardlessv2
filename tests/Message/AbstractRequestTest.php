<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use Mockery;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\GoCardlessV2\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest|\Mockery\MockInterface
     */
    private $request;

    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\GoCardlessV2\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testSetServiceFeeAmountValidation()
    {
        // check blank is formatted
        $this->assertEquals(0.00, $this->request->getServiceFeeAmount());

        // check a real value
        $this->request->setServiceFeeAmount(5.29);
        $this->assertEquals(5.29, $this->request->getServiceFeeAmount());

        // check null is made zero and overwrites the previous value
        $this->request->setServiceFeeAmount(null);
        $this->assertEquals(0.00, $this->request->getServiceFeeAmount());
    }

    public function testSetServiceFeeAmountExceptionValidation()
    {
        // check invalid values error
        $this->setExpectedException(InvalidRequestException::class);
        $this->request->setServiceFeeAmount('Rubbish');
    }
}
