<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Resources\Authorise;
use GoCardlessPro\Services\AuthorisesService;
use Omnipay\Tests\TestCase;

class RedirectAuthoriseResponseTest extends TestCase
{
    /**
     * @var RedirectAuthoriseRequest
     */
    private $request;

    private $data;

    public function setUp()
    {
        $this->data = json_decode(
            '{
        "id":"CB1231235413",
        "redirect_url":"https://this.site.com/redirect"
        }'
        );
        $this->request = $this->getMockBuilder(RedirectAuthoriseRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testFixedFunctions()
    {
        $response = new RedirectAuthoriseResponse($this->request, $this->data);
        $this->assertEquals(false, $response->isSuccessful());
        $this->assertEquals(true, $response->isRedirect());
        $this->assertEquals('GET', $response->getRedirectMethod());
        $this->assertEquals(null, $response->getRedirectData());
    }

    public function testReturnedValues(){
        $response = new RedirectAuthoriseResponse($this->request, $this->data);
        $this->assertEquals('CB1231235413', $response->getRedirectID());
        $this->assertEquals('https://this.site.com/redirect', $response->getRedirectUrl());
    }

}
