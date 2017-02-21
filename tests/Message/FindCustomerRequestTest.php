<?php
namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Tests\TestCase;

class FindCustomerRequestTest extends TestCase
{
    /**
     * @var CreateCustomerRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $gateway = $this->buildMockGateway();
        $this->request = new FindCustomerRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize(array('customerId' => 1));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertNull($data);
    }

    public function testSendData()
    {
        $data = array();
        $response = $this->request->sendData($data);

        $this->assertInstanceOf('Omnipay\GoCardlessV2\Message\CustomerResponse', $response);
    }

    protected function buildMockGateway()
    {
        $gateway = $this->getMockBuilder('\Braintree_Gateway')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'customer'
            ))
            ->getMock();

        $customer = $this->getMockBuilder('\Braintree_CustomerGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $gateway->expects($this->any())
            ->method('customer')
            ->will($this->returnValue($customer));

        return $gateway;
    }
}
