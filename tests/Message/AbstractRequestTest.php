<?php

namespace Omnipay\GoCardlessV2Tests\Message;

use GoCardlessPro\Core\Exception\GoCardlessProException;
use Mockery;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\GoCardlessV2\Message\AbstractRequest;
use Omnipay\GoCardlessV2\Message\CancelMandateRequest;
use Omnipay\GoCardlessV2\Message\ErrorResponse;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest|\Mockery\MockInterface
     */
    private $request;
    private $exceptionCounter = 0;

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

    public function testSecret()
    {
        $value = md5(rand(0, 999999999));
        $this->request->setSecret($value);
        $this->assertEquals($value, $this->request->getSecret());

    }

    public function testGeneralExceptionHandling()
    {
        // using cancelmandaterequest as an arbitrary method - mocking both methods in it so fairly irrelevant which one we use.
        /** @var CancelMandateRequest|\Mockery\MockInterface $mandateRequest */
        $mandateRequest = $this->getMockBuilder(CancelMandateRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'sendData',
                    'getData',
                ]
            )
            ->getMock();
        $mandateRequest->sleepTimeout=1;
        $mandateRequest->expects($this->once())
            ->method('getData')
            ->will($this->returnValue([]));
        $mandateRequest->expects($this->once())
            ->method('sendData')
            ->will($this->returnCallback([$this, 'throwGeneralException']));

        $result = $mandateRequest->send();

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $this->assertEquals(false, $result->isSuccessful());
        $this->assertEquals("General Exception", $result->getMessage());
    }

    public function throwGeneralException()
    {
        throw new \Exception("General Exception");
    }


    public function testGoCardlessExceptionHandling()
    {
        // using cancelmandaterequest as an arbitrary method - mocking both methods in it so fairly irrelevant which one we use.
        /** @var CancelMandateRequest|\Mockery\MockInterface $mandateRequest */
        $mandateRequest = $this->getMockBuilder(CancelMandateRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'sendData',
                    'getData',
                ]
            )
            ->getMock();
        $mandateRequest->sleepTimeout=1;
        $mandateRequest->expects($this->any())
            ->method('getData')
            ->will($this->returnValue([]));
        $mandateRequest->expects($this->any())
            ->method('sendData')
            ->will($this->returnCallback([$this, 'throwGoCardlessGeneralException']));
        $result = $mandateRequest->send();
        $this->assertInstanceOf(ErrorResponse::class, $result);
        $this->assertEquals(false, $result->isSuccessful());
        $this->assertEquals("GoCardless Pro Exception", $result->getMessage());
    }

    public function throwGoCardlessGeneralException()
    {
        throw new GoCardlessProException("GoCardless Pro Exception");
    }


    public function testGoCardlessRateLimitExceptionHandling()
    {
        // using cancelmandaterequest as an arbitrary method - mocking both methods in it so fairly irrelevant which one we use.
        /** @var CancelMandateRequest|\Mockery\MockInterface $mandateRequest */
        $mandateRequest = $this->getMockBuilder(CancelMandateRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'sendData',
                    'getData',
                ]
            )
            ->getMock();
        $mandateRequest->sleepTimeout=1;
        $mandateRequest->expects($this->any())
            ->method('getData')
            ->will($this->returnValue([]));
        $mandateRequest->expects($this->exactly(2))
            ->method('sendData')
            ->will($this->returnCallback([$this, 'throwGoCardlessRateException']));

        $result = $mandateRequest->send();

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $this->assertEquals(false, $result->isSuccessful());
        $this->assertEquals("Rate limit exceeded", $result->getMessage());
    }

    public function throwGoCardlessRateException()
    {
        throw new GoCardlessProException("Rate limit exceeded");
    }


    public function testGoCardlessRateLimitThenSuccessHandling()
    {
        // using cancelmandaterequest as an arbitrary method - mocking both methods in it so fairly irrelevant which one we use.
        /** @var CancelMandateRequest|\Mockery\MockInterface $mandateRequest */
        $mandateRequest = $this->getMockBuilder(CancelMandateRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'sendData',
                    'getData',
                ]
            )
            ->getMock();
        $mandateRequest->sleepTimeout=1;
        $mandateRequest->expects($this->any())
            ->method('getData')
            ->will($this->returnValue([]));
        $mandateRequest->expects($this->exactly(2))
            ->method('sendData')
            ->will($this->returnCallback([$this, 'sometimesThrowGoCardlessRateException']));

        $result = $mandateRequest->send();
        $this->assertEquals(1, $result);
    }

    public function sometimesThrowGoCardlessRateException()
    {
        ++$this->exceptionCounter;
        if ($this->exceptionCounter == 1) {
            throw new GoCardlessProException('Rate limit exceeded');
        } else {
            return 1;
        }
    }
}
