<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client;
use GoCardlessPro\Resources\Mandate;
use GoCardlessPro\Services\MandatesService;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class InvalidRequestExceptionTest extends TestCase
{
    /**
     * @var CancelMandateRequest
     */
    private $request;

    /**
     * @var array fully populated sample mandate data to drive test
     */
    private $sampleData = array(
        'mandateId' => 'CU123123123',
    );

    public function setUp()
    {
        $gateway = new Client(array('access_token' => 'foobar', 'environment' => 'sandbox'));

        $this->request = new CancelMandateRequest($this->getHttpClient(), $this->getHttpRequest(), $gateway);
        $this->request->initialize($this->sampleData);
    }

    public function testExceptionThrown()
    {
        // this should be blank
        $this->setExpectedException(InvalidRequestException::class);
        $this->request->send();
    }

}
