<?php

namespace Omnipay\GoCardlessV2\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use GoCardlessPro\Client as GoCardlessClient;

/**
 * Abstract Request
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    const LIVE_OAUTH_URL = 'https://connect.gocardless.com/oauth';
    const TEST_OAUTH_URL = 'https://connect-sandbox.gocardless.com/oauth';

    /**
     * @var GoCardlessClient
     */
    public $gocardless;

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient A Guzzle client to make API calls with
     * @param HttpRequest $httpRequest A Symfony HTTP request object
     * @param GoCardlessClient $gocardless The GoCardless Client
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest, GoCardlessClient $gocardless = null)
    {
        $this->gocardless = $gocardless;

        parent::__construct($httpClient, $httpRequest);
    }

    /**
     * Set the correct configuration sending
     *
     * @return \Omnipay\Common\Message\ResponseInterface
     *
     * @throws InvalidRequestException
     */
    public function send()
    {
        try {
            return $this->sendData($this->getData());
        } catch (\GoCardlessPro\Core\Exception\GoCardlessProException $e) {
            throw new InvalidRequestException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getOAuthUrl()
    {
        return $this->getTestMode() ? self::TEST_OAUTH_URL : self::LIVE_OAUTH_URL;
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getOAuthSecret()
    {
        return $this->getParameter('oAuthSecret');
    }

    public function setOAuthSecret($value)
    {
        return $this->setParameter('oAuthSecret', $value);
    }

    public function getOAuthScope()
    {
        return $this->getParameter('oauthScope') ?: 'read_only';
    }

    public function setOAuthScope($value)
    {
        if ($value !== 'read_write') {
            $value = 'read_only';
        }

        return $this->setParameter('oauthScope', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getCustomerBankAccountData()
    {
        return $this->getParameter('customerBankAccountData');
    }

    /**
     * @param array $value
     *  [
     *      'account_holder_name', // mandatory
     *      'account_number',
     *      'bank_code',
     *      'branch_code',
     *      'country_code', // iso 3166-1 alpha-2
     *      'currency', // iso 4217
     *      'iban',
     *      'metadata', //array of up to 3 fields of key (Char 50) : value (Char 500)
     *  ]
     *
     * @return BaseAbstractRequest
     */
    public function setCustomerBankAccountData(array $value)
    {
        return $this->setParameter('customerBankAccountData', $value);
    }

    public function getMandateData()
    {
        return $this->getParameter('mandateData');
    }

    /**
     * @param array $value
     *  [
     *      'reference',
     *      'scheme',
     *      'metadata', //array of up to 3 fields of key (Char 50) : value (Char 500)
     *  ]
     *
     * @return BaseAbstractRequest
     */
    public function setMandateData(array $value)
    {
        return $this->setParameter('mandateData', $value);
    }

    public function setCustomerBankAccountToken($value)
    {
        return $this->setParameter('customerBankAccountToken', $value);
    }

    public function getCustomerBankAccountToken()
    {
        return $this->getParameter('customerBankAccountToken');
    }

    public function getBankAccountReference()
    {
        return $this->getParameter('customerBankAccountId');
    }

    public function setBankAccountReference($value)
    {
        return $this->setParameter('customerBankAccountId', $value);
    }

    public function getCreditorId()
    {
        return $this->getParameter('creditorId');
    }

    public function setCreditorId($value)
    {
        return $this->setParameter('creditorId', $value);
    }

    public function getReference()
    {
        return $this->getParameter('reference');
    }

    public function setReference($value)
    {
        return $this->setParameter('reference', $value);
    }

    public function getMandateReference()
    {
        return $this->getParameter('mandateReference');
    }

    public function setMandateReference($value)
    {
        return $this->setParameter('mandateReference', $value);
    }

    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    public function getPaymentId()
    {
        return $this->getParameter('paymentId');
    }

    public function setPaymentId($value)
    {
        return $this->setParameter('paymentId', $value);
    }

    public function getEventId()
    {
        return $this->getParameter('eventId');
    }

    public function setEventId($value)
    {
        return $this->setParameter('eventId', $value);
    }

    public function getSubscriptionId()
    {
        return $this->getParameter('subscriptionId');
    }

    public function setSubscriptionId($value)
    {
        return $this->setParameter('subscriptionId', $value);
    }

    public function getPaymentDescription()
    {
        return $this->getParameter('description');
    }

    public function setPaymentDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    public function getSwedishIdentityNumber()
    {
        return $this->getParameter('swedishIdentityNumber');
    }

    public function setSwedishIdentityNumber($value)
    {
        return $this->setParameter('swedishIdentityNumber', $value);
    }

    public function setPaymentMetaData(array $value)
    {
        return $this->setParameter('paymentMetaData', $value);
    }

    public function getPaymentMetaData()
    {
        return $this->getParameter('paymentMetaData');
    }

    public function setSubscriptionMetaData(array $value)
    {
        return $this->setParameter('subscriptionMetaData', $value);
    }

    public function getSubscriptionMetaData()
    {
        return $this->getParameter('subscriptionMetaData');
    }

    public function setCustomerMetaData(array $value)
    {
        return $this->setParameter('customerMetaData', $value);
    }

    public function getCustomerMetaData()
    {
        return $this->getParameter('customerMetaData');
    }

    public function getTotalRefundedAmount()
    {
        return $this->getParameter('totalRefundedAmount');
    }

    public function setTotalRefundedAmount($value)
    {
        return $this->setParameter('totalRefundedAmount', $value);
    }

    public function getServiceFeeAmount()
    {
        return $this->getParameter('serviceFeeAmount') ? $this->formatCurrency($this->getParameter('serviceFeeAmount')) : null;
    }

    public function setServiceFeeAmount($value)
    {
        if (!empty($value)) {
            if (!is_float($value) &&
                $this->getCurrencyDecimalPlaces() > 0 &&
                false === strpos((string) $value, '.')
            ) {
                throw new InvalidRequestException(
                    'Please specify amount as a string or float, '.
                    'with decimal places (e.g. \'10.00\' to represent $10.00).'
                );
            }
        } else {
            $value = null;
        }

        return $this->setParameter('serviceFeeAmount', $value);
    }

    public function getPaymentDate()
    {
        return $this->getParameter('paymentDate');
    }

    public function setPaymentDate($value)
    {
        return $this->setParameter('paymentDate', $value);
    }

    /**
     * Set the plan statement descriptor
     */
    public function setStatementDescriptor($planStatementDescriptor)
    {
        return $this->setParameter('statement_descriptor', $planStatementDescriptor);
    }

    /**
     * Get the plan statement descriptor
     */
    public function getStatementDescriptor()
    {
        return $this->getParameter('statement_descriptor');
    }

    public function getSubscriptionDayOfMonth()
    {
        return $this->getParameter('subscriptionDayOfMonth');
    }

    public function setSubscriptionDayOfMonth($value)
    {
        return $this->setParameter('subscriptionDayOfMonth', $value);
    }

    /**
     * @return string|null
     */
    public function getInterval()
    {
        return $this->getParameter('subscription_interval_unit');
    }

    /**
     * @param string $planInterval
     * @return BaseAbstractRequest
     * @throws InvalidRequestException
     */
    public function setInterval($planInterval)
    {
        $valid = ['weekly', 'monthly', 'yearly'];
        if (!in_array($planInterval, $valid)) {
            throw new InvalidRequestException('Interval must be one of '.implode(' / ', $valid));
        }

        return $this->setParameter('subscription_interval_unit', $planInterval);
    }

    public function getIntervalCount()
    {
        return $this->getParameter('subscription_interval_count');
    }

    public function setIntervalCount($value)
    {
        return $this->setParameter('subscription_interval_count', $value);
    }

    public function getSubscriptionMonth()
    {
        return $this->getParameter('subscriptionMonth');
    }

    public function setSubscriptionMonth($value)
    {
        return $this->setParameter('subscriptionMonth', $value);
    }

    public function getSubscriptionCount()
    {
        return $this->getParameter('subscriptionCount');
    }

    public function setSubscriptionCount($value)
    {
        return $this->setParameter('subscriptionCount', $value);
    }

    public function getSubscriptionEndDate()
    {
        return $this->getParameter('subscriptionEndDate');
    }

    public function setSubscriptionEndDate($value)
    {
        return $this->setParameter('subscriptionEndDate', $value);
    }
}
