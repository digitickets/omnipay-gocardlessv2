<?php

namespace Omnipay\GoCardlessV2;

use GoCardlessPro\Client as GoCardlessClient;
use GoCardlessPro\Environment;
use Guzzle\Http\ClientInterface;
use Omnipay\Common\AbstractGateway as BaseAbstractGateway;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\GoCardlessV2\Message\AbstractRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

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
    protected $gocardless;

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
            [
                'access_token' => '',
                'environment' => Environment::SANDBOX, // default a blank to sandbox
            ]
        );

        parent::__construct($httpClient, $httpRequest);
    }

    public function initialize(array $parameters = [])
    {
        parent::initialize($parameters);
        if ($parameters && array_key_exists('access_token', $parameters)) {
            $this->gocardless = new GoCardlessClient(
                [
                    'access_token' => $parameters['access_token'],
                    'environment' => Environment::LIVE,
                ]
            );
        }
    }

    public function setTestMode($value)
    {
        if ($value && $this->getParameter('access_token')) {
            $this->gocardless = new GoCardlessClient(
                [
                    'access_token' => $this->getParameter('access_token'),
                    'environment' => Environment::SANDBOX,
                ]
            );
        }

        return parent::setTestMode($value);
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('access_token', $value);
    }

    public function getAccessToken()
    {
        return $this->getParameter('access_token');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
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
        return [
            'accessToken' => '',
            'testMode' => true,
        ];
    }

    /**
     * @param string $id
     *
     * @return Message\FindCustomerRequest|Message\AbstractRequest
     */
    public function findCustomer($id)
    {
        return $this->createRequest(Message\FindCustomerRequest::class, ['customerReference' => $id]);
    }

    /**
     * @param array $parameters
     *
     * @return Message\UpdateCustomerRequest|Message\AbstractRequest
     */
    public function updateCustomer(array $parameters = [])
    {
        return $this->createRequest(Message\UpdateCustomerRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\ValidateBankAccountRequest|Message\AbstractRequest
     */
    public function validateBankAccount(array $parameters = [])
    {
        return $this->createRequest(Message\ValidateBankAccountRequest::class, $parameters);
    }

    /**
     * @param $id
     *
     * @return Message\FindCustomerBankAccountRequest|Message\AbstractRequest
     */
    public function findCustomerBankAccount($id)
    {
        return $this->createRequest(Message\FindCustomerBankAccountRequest::class, ['bankAccountReference' => $id]);
    }

    /**
     * @param $id
     *
     * @return Message\DisableCustomerBankAccountRequest|Message\AbstractRequest
     */
    public function disableCustomerBankAccount($id)
    {
        return $this->createRequest(Message\DisableCustomerBankAccountRequest::class, ['bankAccountReference' => $id]);
    }

    /**
     * @param array $parameters
     *
     * @return Message\UpdateCustomerBankAccountRequest|Message\AbstractRequest
     */
    public function updateCustomerBankAccount(array $parameters = [])
    {
        return $this->createRequest(Message\UpdateCustomerBankAccountRequest::class, $parameters);
    }

    /**
     * @param $id
     *
     * @return Message\FindMandateRequest|Message\AbstractRequest
     */
    public function findMandate($id)
    {
        return $this->createRequest(Message\FindMandateRequest::class, ['mandateReference' => $id]);
    }

    /**
     * @param $id
     *
     * @return Message\CancelMandateRequest|Message\AbstractRequest
     */
    public function cancelMandate($id)
    {
        return $this->createRequest(Message\CancelMandateRequest::class, ['mandateReference' => $id]);
    }

    /**
     * @param $id
     *
     * @return Message\ReinstateMandateRequest|Message\AbstractRequest
     */
    public function reinstateMandate($id)
    {
        return $this->createRequest(Message\ReinstateMandateRequest::class, ['mandateReference' => $id]);
    }

    /**
     * @param array $parameters
     *
     * @return Message\UpdateMandateRequest|Message\AbstractRequest
     */
    public function updateMandate(array $parameters = [])
    {
        return $this->createRequest(Message\UpdateMandateRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\CreatePaymentRequest|Message\AbstractRequest
     */
    public function createPayment(array $parameters = [])
    {
        return $this->createRequest(Message\CreatePaymentRequest::class, $parameters);
    }

    /**
     * @param $id
     *
     * @return Message\FindPaymentRequest|Message\AbstractRequest
     */
    public function findPayment($id)
    {
        return $this->createRequest(Message\FindPaymentRequest::class, ['paymentId' => $id]);
    }

    /**
     * @param $id
     *
     * @return Message\CancelPaymentRequest|Message\AbstractRequest
     */
    public function cancelPayment($id)
    {
        return $this->createRequest(Message\CancelPaymentRequest::class, ['paymentId' => $id]);
    }

    /**
     * @param array $parameters
     *
     * @return Message\RetryPaymentRequest|Message\AbstractRequest
     */
    public function retryPayment(array $parameters = [])
    {
        return $this->createRequest(Message\RetryPaymentRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\UpdatePaymentRequest|Message\AbstractRequest
     */
    public function updatePayment(array $parameters = [])
    {
        return $this->createRequest(Message\UpdatePaymentRequest::class, $parameters);
    }

    /**
     * proxy the more clearly named createPayment method
     *
     * @param array $parameters
     *
     * @return Message\CreatePaymentRequest|Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createPayment($parameters);
    }

    /**
     * Refund endpoint only enabled by request
     *
     * @param array $parameters
     *
     * @return Message\CreateRefundRequest|Message\AbstractRequest
     */
    public function createRefund(array $parameters = [])
    {
        return $this->createRequest(Message\CreateRefundRequest::class, $parameters);
    }

    /**
     * @param $id
     *
     * @return Message\FindRefundRequest|Message\AbstractRequest
     */
    public function findRefund($id)
    {
        return $this->createRequest(Message\FindRefundRequest::class, ['transactionReference' => $id]);
    }

    /**
     * @param array $parameters
     *
     * @return Message\UpdateRefundRequest|Message\AbstractRequest
     */
    public function updateRefund(array $parameters = [])
    {
        return $this->createRequest(Message\UpdateRefundRequest::class, $parameters);
    }

    /**
     * Only enabled by request
     * wraps createRefund
     *
     * @param array $parameters
     *
     * @return Message\CreateRefundRequest|Message\AbstractRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRefund($parameters);
    }

    /**
     * @param array $parameters
     *
     * @return Message\CreateSubscriptionRequest|Message\AbstractRequest
     */
    public function createSubscription(array $parameters = [])
    {
        return $this->createRequest(Message\CreateSubscriptionRequest::class, $parameters);
    }

    /**
     * @param string $subscriptionId
     *
     * @return Message\CancelSubscriptionRequest|Message\AbstractRequest
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->createRequest(Message\CancelSubscriptionRequest::class, ['subscriptionId' => $subscriptionId]);
    }

    /**
     * @param string $subscriptionId
     *
     * @return Message\FindSubscriptionRequest|Message\AbstractRequest
     */
    public function findSubscription($subscriptionId)
    {
        return $this->createRequest(Message\FindSubscriptionRequest::class, ['subscriptionId' => $subscriptionId]);
    }

    /**
     * @param array $parameters
     *
     * @return Message\UpdateSubscriptionRequest|Message\AbstractRequest
     */
    public function updateSubscription(array $parameters = [])
    {
        return $this->createRequest(Message\UpdateSubscriptionRequest::class, $parameters);
    }

    /**
     * Start the oauth request process for linking a merchant account to your application
     *
     * @param array $parameters
     *
     * @return Message\OAuthRequest|Message\AbstractRequest
     */
    public function requestOAuth(array $parameters = [])
    {
        return $this->createRequest(Message\OAuthRequest::class, $parameters);
    }

    /**
     * Complete the oauth request process for linking a merchant account to your application
     *
     * @param array $parameters
     *
     * @return Message\OAuthConfirmRequest|Message\AbstractRequest
     */
    public function confirmOAuth(array $parameters = [])
    {
        return $this->createRequest(Message\OAuthConfirmRequest::class, $parameters);
    }

    /**
     * @param $eventId
     *
     * @return Message\FindEventRequest|Message\AbstractRequest
     */
    public function findEvent($eventId)
    {
        return $this->createRequest(Message\FindEventRequest::class, ['eventId' => $eventId]);
    }

    /**
     * Find all payments related to the specified Customer
     *
     * @param array $parameters
     *
     * @return Message\FindPaymentsByCustomerRequest|Message\AbstractRequest
     */
    public function findPaymentsByCustomer(array $parameters = [])
    {
        return $this->createRequest(Message\FindPaymentsByCustomerRequest::class, $parameters);
    }

    /**
     * Find all payments for specified Subscription
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest|AbstractRequest
     */
    public function findPaymentsBySubscription(array $parameters = [])
    {
        return $this->createRequest(Message\FindPaymentsBySubscriptionRequest::class, $parameters);
    }

    public function findSubscriptionsByCustomer(array $parameters = [])
    {
        return $this->createRequest(Message\FindSubscriptionsByCustomerRequest::class, $parameters);
    }
    
    /**
     * attempt to process the data from the webhooks
     * fetches the latest version of each eventID (as per GoCardless documentation)
     * returns an array of events
     * Doesn't work with multiple companies for authorised partner applications - the webhook message payload can contain multiple companies
     * and therefore multiple api keys are needed to get the event details. Instead use authenticateNotification then process each event individually
     *
     * @param string $rawPayload - file_get_contents('php://input');
     * @param string $provided_signature - $_SERVER['HTTP_WEBHOOK_SIGNATURE'];
     *
     * @return Message\EventResponse[]
     *
     * @throws InvalidResponseException
     */
    public function parseNotification($rawPayload, $provided_signature = null)
    {
        $return = [];
        if (!$this->authenticateNotification($rawPayload, $provided_signature)) {
            throw new InvalidResponseException('Invalid security token from webhook response');
        }
        $payload = json_decode($rawPayload, true);
        if (array_key_exists('events', $payload) && is_array($payload['events'])) {
            foreach ($payload['events'] as $event) {
                $return[] = $this->findEvent($event['id'])->send();
            }
        }

        return $return;
    }

    /**
     * helper function to verify the signature on the header of the file
     *
     * @param string $rawPayload - file_get_contents('php://input');
     * @param string $provided_signature - $_SERVER['HTTP_WEBHOOK_SIGNATURE'];
     *
     * @return bool
     */
    public function authenticateNotification($rawPayload, $provided_signature = null)
    {
        if ($this->getParameter('secret')) {// validate
            $calculated_signature = hash_hmac('sha256', $rawPayload, $this->getParameter('secret'));
            if ($provided_signature != $calculated_signature) {
                return false;
            }
        }

        return true;
    }
}
