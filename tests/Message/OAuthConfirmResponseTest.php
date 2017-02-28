<?php
namespace Omnipay\GoCardlessV2\Message;

use Omnipay\Tests\TestCase;

class OAuthConfirmResponseTest extends TestCase
{
    /**
     * @var OAuthConfirmRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(OAuthConfirmRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCompleteAuthoriseData()
    {
        $data = json_decode('{"access_token":"access_token","scope":"scope","token_type":"token_type","organisation_id":"organisation_id"}');

        $response = new OAuthConfirmResponse($this->request, $data);
        $this->assertEquals('access_token', $response->getAccessToken());
        $this->assertEquals('scope', $response->getScope());
        $this->assertEquals('token_type', $response->getTokenType());
        $this->assertEquals('organisation_id', $response->getOrganisationID());
    }

    public function testFailedCompleteAuthoriseData()
    {
        $data = null;

        $response = new OAuthConfirmResponse($this->request, $data);
        $this->assertNull($response->getAccessToken());
        $this->assertNull($response->getScope());
        $this->assertNull($response->getTokenType());
        $this->assertNull($response->getOrganisationID());
    }

}
