<?php

namespace Omnipay\GoCardlessV2Tests;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\GoCardlessV2\ProGateway;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\GoCardlessV2\Message;

/**
 * Class ProGatewayTest
 * This also tests the base abstract gateway.
 */
class CoreGatewayTest extends GatewayTestCase
{
    /**
     * @var ProGateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ProGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testInitialise()
    {
        $this->gateway->initialize(['access_token'=>'fooBar', 'secret'=>'secret']);
    }

    public function testSetTestMode()
    {
        $this->testInitialise();
        $this->gateway->setTestMode(false);
        $this->gateway->setTestMode(true);
    }

    public function testFindCustomer()
    {
        $request = $this->gateway->findCustomer(1);
        $this->assertInstanceOf(Message\FindCustomerRequest::class, $request);
        $this->assertEquals(1, $request->getCustomerReference());
    }

    public function testUpdateCustomer()
    {
        $request = $this->gateway->updateCustomer();
        $this->assertInstanceOf(Message\UpdateCustomerRequest::class, $request);
    }

    public function testFindCustomerBankAccount()
    {
        $request = $this->gateway->findCustomerBankAccount(1);
        $this->assertInstanceOf(Message\FindCustomerBankAccountRequest::class, $request);
        $this->assertEquals(1, $request->getBankAccountReference());
    }

    public function testUpdateCustomerBankAccount()
    {
        $request = $this->gateway->updateCustomerBankAccount();
        $this->assertInstanceOf(Message\UpdateCustomerBankAccountRequest::class, $request);
    }

    public function testDisableCustomerBankAccount()
    {
        $request = $this->gateway->disableCustomerBankAccount(1);
        $this->assertInstanceOf(Message\DisableCustomerBankAccountRequest::class, $request);
        $this->assertEquals(1, $request->getBankAccountReference());
    }

    public function testFindMandate()
    {
        $request = $this->gateway->findMandate(1);
        $this->assertInstanceOf(Message\FindMandateRequest::class, $request);
        $this->assertEquals(1, $request->getMandateReference());
    }

    public function testUpdateMandate()
    {
        $request = $this->gateway->updateMandate();
        $this->assertInstanceOf(Message\UpdateMandateRequest::class, $request);
    }

    public function testCancelMandate()
    {
        $request = $this->gateway->cancelMandate(1);
        $this->assertInstanceOf(Message\CancelMandateRequest::class, $request);
        $this->assertEquals(1, $request->getMandateReference());
    }

    public function testReinstateMandate()
    {
        $request = $this->gateway->reinstateMandate(1);
        $this->assertInstanceOf(Message\ReinstateMandateRequest::class, $request);
        $this->assertEquals(1, $request->getMandateReference());
    }

    public function testFindPayment()
    {
        $request = $this->gateway->findPayment(1);
        $this->assertInstanceOf(Message\FindPaymentRequest::class, $request);
        $this->assertEquals(1, $request->getPaymentId());
    }

    public function testCreatePayment()
    {
        $request = $this->gateway->createPayment();
        $this->assertInstanceOf(Message\CreatePaymentRequest::class, $request);
    }

    public function testUpdatePayment()
    {
        $request = $this->gateway->updatePayment();
        $this->assertInstanceOf(Message\UpdatePaymentRequest::class, $request);
    }

    public function testCancelPayment()
    {
        $request = $this->gateway->cancelPayment(1);
        $this->assertInstanceOf(Message\CancelPaymentRequest::class, $request);
        $this->assertEquals(1, $request->getPaymentId());
    }

    public function testRetryPayment()
    {
        $request = $this->gateway->retryPayment();
        $this->assertInstanceOf(Message\RetryPaymentRequest::class, $request);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(['amount' => '10.00']);
        $this->assertInstanceOf(Message\CreatePaymentRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(['amount' => '10.00']);
        $this->assertInstanceOf(Message\CreateRefundRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testFindRefund()
    {
        $request = $this->gateway->findRefund(1);
        $this->assertInstanceOf(Message\FindRefundRequest::class, $request);
        $this->assertEquals(1, $request->getTransactionReference());
    }

    public function testUpdateRefund()
    {
        $request = $this->gateway->updateRefund();
        $this->assertInstanceOf(Message\UpdateRefundRequest::class, $request);
    }

    public function testCreateSubscription()
    {
        $request = $this->gateway->createSubscription([]);
        $this->assertInstanceOf(Message\CreateSubscriptionRequest::class, $request);
    }

    public function testCancelSubscription()
    {
        $request = $this->gateway->cancelSubscription('1');
        $this->assertInstanceOf(Message\CancelSubscriptionRequest::class, $request);
    }

    public function testFindSubscription()
    {
        $request = $this->gateway->findSubscription(1);
        $this->assertInstanceOf(Message\FindSubscriptionRequest::class, $request);
        $this->assertEquals(1, $request->getSubscriptionId());
    }

    public function testUpdateSubscription()
    {
        $request = $this->gateway->updateSubscription();
        $this->assertInstanceOf(Message\UpdateSubscriptionRequest::class, $request);
    }

    public function testRequestOAuth()
    {
        $request = $this->gateway->requestOAuth();
        $this->assertInstanceOf(Message\OAuthRequest::class, $request);
    }

    public function testConfirmOAuth()
    {
        $request = $this->gateway->confirmOAuth();
        $this->assertInstanceOf(Message\OAuthConfirmRequest::class, $request);
    }

    public function testFindEvent()
    {
        $request = $this->gateway->findEvent(1);
        $this->assertInstanceOf(Message\FindEventRequest::class, $request);
        $this->assertEquals(1, $request->getEventId());
    }

    public function testFailedWebHookAuthentication()
    {
        $this->testInitialise();
        $this->gateway->setTestMode(true);
        $this->setExpectedException(InvalidResponseException::class, 'Invalid security token from webhook response');
        $this->gateway->parseNotification(
            '{"events": [
    {"id": "EV123", "created_at": "2014-08-03T12:00:00.000Z", "action": "confirmed","resource_type": "payments",}}',
        123
        );
    }

    public function testSuccessfulWebHookAuthentication()
    {
        $body = '{"hello":"world"}';
        $this->testInitialise();
        $this->gateway->setTestMode(true);
        $response = $this->gateway->parseNotification(
            $body,
            hash_hmac('sha256', $body, 'secret')
        );
        $this->assertEquals([], $response);
    }

}
