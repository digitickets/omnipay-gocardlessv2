<?php

namespace Omnipay\GoCardlessV2;

use GoCardlessPro\Environment;
use Omnipay\Common\AbstractGateway as BaseAbstractGateway;
use Guzzle\Http\ClientInterface;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\GoCardlessV2\Message\AbstractRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use GoCardlessPro\Client as GoCardlessClient;

/**
 * GoCardless Gateway
 *
 * @method RequestInterface authorize(array $options = array())         (Optional method)
 *         Authorize an amount on the customers card
 * @method RequestInterface completeAuthorize(array $options = array()) (Optional method)
 *         Handle return from off-site gateways after authorization
 * @method RequestInterface capture(array $options = array())           (Optional method)
 *         Capture an amount you have previously authorized
 * @method RequestInterface completePurchase(array $options = array())  (Optional method)
 *         Handle return from off-site gateways after purchase
 * @method RequestInterface void(array $options = array())              (Optional method)
 *         Generally can only be called up to 24 hours after submitting a transaction
 * @method RequestInterface createCard(array $options = array())        (Optional method)
 *         The returned response object includes a cardReference, which can be used for future transactions
 * @method RequestInterface updateCard(array $options = array())        (Optional method)
 *         Update a stored card
 * @method RequestInterface deleteCard(array $options = array())        (Optional method)
 *         Delete a stored card
 */
abstract class AbstractGateway extends BaseAbstractGateway
{
    /**
     * @var GoCardlessClient
     */
    private $gocardless;

    /**
     * Create a new gateway instance
     *
     * @param ClientInterface $httpClient A Guzzle client to make API calls with
     * @param HttpRequest $httpRequest A Symfony HTTP request object
     * @param GoCardlessClient $gocardless The GoCardless Client
     */
    public function __construct(
        ClientInterface $httpClient = null,
        HttpRequest $httpRequest = null,
        GoCardlessClient $gocardless = null
    ) {
        $this->gocardless = $gocardless ?: new GoCardlessClient(
            array(
                'access_token' => '',
                'environment' => Environment::SANDBOX, // default a blank to sandbox
            )
        );

        parent::__construct($httpClient, $httpRequest);
    }

    /**
     * {@inheritdoc}
     */
    protected function createRequest($class, array $parameters)
    {
        /**
         * @var AbstractRequest $obj
         */
        $obj = new $class($this->httpClient, $this->httpRequest, $this->gocardless);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    public function getName()
    {
        return 'GoCardlessV2';
    }

    public function getDefaultParameters()
    {
        return array();
    }

    /**
     * @param string $id
     * @return Message\CustomerResponse|Message\AbstractRequest
     */
    public function findCustomer($id)
    {
        return $this->createRequest(Message\FindCustomerRequest::class, array('customerId' => $id));
    }

    /**
     * @param array $parameters
     * @return Message\CustomerResponse|Message\AbstractRequest
     */
    public function updateCustomer(array $parameters = array())
    {
        return $this->createRequest(Message\UpdateCustomerRequest::class, $parameters);
    }


    /**
     * @param $id
     * @return Message\CustomerBankAccountResponse|Message\AbstractRequest
     */
    public function findCustomerBankAccount($id)
    {
        return $this->createRequest(Message\FindCustomerBankAccountRequest::class, array('customerBankAccountId' => $id));
    }

    /**
     * @param $id
     * @return Message\CustomerBankAccountResponse|Message\AbstractRequest
     */
    public function disableCustomerBankAccount($id)
    {
        return $this->createRequest(Message\DisableCustomerBankAccountRequest::class, array('customerBankAccountId' => $id));
    }

    /**
     * @param array $parameters
     * @return Message\CustomerBankAccountResponse|Message\AbstractRequest
     */
    public function updateCustomerBankAccount(array $parameters = array())
    {
        return $this->createRequest(Message\UpdateCustomerBankAccountRequest::class, $parameters);
    }

    /**
     * @param $id
     * @return Message\MandateResponse|Message\AbstractRequest
     */
    public function findMandate($id)
    {
        return $this->createRequest(Message\FindMandateRequest::class, array('mandateID' => $id));
    }

    /**
     * @param $id
     * @return Message\MandateResponse|Message\AbstractRequest
     */
    public function cancelMandate($id)
    {
        return $this->createRequest(Message\CancelMandateRequest::class, array('mandateID' => $id));
    }

    /**
     * @param $id
     * @return Message\MandateResponse|Message\AbstractRequest
     */
    public function reinstateMandate($id)
    {
        return $this->createRequest(Message\ReinstateMandateRequest::class, array('mandateID' => $id));
    }

    /**
     * @param array $parameters
     * @return Message\MandateResponse|Message\AbstractRequest
     */
    public function updateMandate(array $parameters = array())
    {
        return $this->createRequest(Message\UpdateMandateRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\PaymentResponse|Message\AbstractRequest
     */
    public function createPayment(array $parameters = array())
    {
        return $this->createRequest(Message\CreatePaymentRequest::class, $parameters);
    }

    /**
     * @param $id
     * @return Message\PaymentResponse|Message\AbstractRequest
     */
    public function findPayment($id)
    {
        return $this->createRequest(Message\FindPaymentRequest::class, array('paymentId' => $id));
    }

    /**
     * @param $id
     * @return Message\PaymentResponse|Message\AbstractRequest
     */
    public function cancelPayment($id)
    {
        return $this->createRequest(Message\CancelPaymentRequest::class, array('paymentId' => $id));
    }

    /**
     * @param array $parameters
     * @return Message\PaymentResponse|Message\AbstractRequest
     */
    public function retryPayment(array $parameters = array())
    {
        return $this->createRequest(Message\RetryPaymentRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\PaymentResponse|Message\AbstractRequest
     */
    public function updatePayment(array $parameters = array())
    {
        return $this->createRequest(Message\UpdatePaymentRequest::class, $parameters);
    }

    /**
     * proxy the more clearly named createPayment method
     *
     * @param array $parameters
     * @return Message\PaymentResponse|Message\AbstractRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createPayment($parameters);
    }


    /**
     * Refund endpoint only enabled by request
     *
     * @param array $parameters
     * @return Message\RefundResponse|Message\AbstractRequest
     */
    public function createRefund(array $parameters = array())
    {
        return $this->createRequest(Message\CreateRefundRequest::class, $parameters);
    }

    /**
     * @param $id
     * @return Message\RefundResponse|Message\AbstractRequest
     */
    public function findRefund($id)
    {
        return $this->createRequest(Message\FindRefundRequest::class, array('transactionReference' => $id));
    }

    /**
     * @param array $parameters
     * @return Message\RefundResponse|Message\AbstractRequest
     */
    public function updateRefund(array $parameters = array())
    {
        return $this->createRequest(Message\UpdateRefundRequest::class, $parameters);
    }

    /**
     * Only enabled by request
     * wraps createRefund
     *
     * @param array $parameters
     * @return Message\RefundResponse|Message\AbstractRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRefund($parameters);
    }


    /**
     * @param array $parameters
     *
     * @return Message\SubscriptionResponse|Message\AbstractRequest
     */
    public function createSubscription(array $parameters = array())
    {
        return $this->createRequest(Message\CreateSubscriptionRequest::class, $parameters);
    }

    /**
     * @param string $subscriptionId
     *
     * @return Message\SubscriptionResponse|Message\AbstractRequest
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->createRequest(Message\CancelSubscriptionRequest::class, array('subscriptionId' => $subscriptionId));
    }

    /**
     * @param string $subscriptionId
     *
     * @return Message\SubscriptionResponse|Message\AbstractRequest
     */
    public function findSubscription($subscriptionId)
    {
        return $this->createRequest(Message\FindSubscriptionRequest::class, array('subscriptionId' => $subscriptionId));
    }

    /**
     * @param array $parameters
     *
     * @return Message\SubscriptionResponse|Message\AbstractRequest
     */
    public function updateSubscription(array $parameters = array())
    {
        return $this->createRequest(Message\UpdateSubscriptionRequest::class, $parameters);
    }

    /**
     * Start the oauth request process for linking a merchant account to your application
     *
     * @param array $parameters
     * @return Message\OAuthResponse|Message\AbstractRequest
     */
    public function requestOAuth(array $parameters = array())
    {
        return $this->createRequest(Message\OAuthRequest::class, $parameters);
    }

    /**
     * Complete the oauth request process for linking a merchant account to your application
     *
     * @param array $parameters
     * @return Message\OAuthConfirmResponse|Message\AbstractRequest
     */
    public function confirmOAuth(array $parameters = array())
    {
        return $this->createRequest(Message\OAuthConfirmRequest::class, $parameters);
    }

    /**
     * @param $eventId
     * @return Message\EventResponse|Message\AbstractRequest
     */
    public function findEvent($eventId)
    {
        return $this->createRequest(Message\FindEventRequest::class, array('eventId' => $eventId));
    }

    /**
     * attempt to process the data from the webhooks
     * fetches the latest version of each eventID (as per GoCardless documentation)
     * returns an array of events
     *
     * @param array $headers - getallheaders();
     * @param string $rawPayload - file_get_contents('php://input');
     * @param string $securityToken - WebHook secret
     *
     * @return Message\EventResponse[]|Message\AbstractRequest[]
     *
     * @throws InvalidResponseException
     */
    public function parseWebHooks(array $headers, $rawPayload, $securityToken = null)
    {
        $return = array();
        if ($securityToken) {// validate
            $provided_signature = $headers["Webhook-Signature"];
            $calculated_signature = hash_hmac("sha256", $rawPayload, $securityToken);
            if ($provided_signature != $calculated_signature) {
                throw new InvalidResponseException("Invalid security token from webhook response");
            }
        }
        $payload = json_decode($rawPayload, true);
        foreach ($payload['events'] as $event) {
            $array[] = $this->findEvent($event['id']);
        }

        return $return;
    }
}
