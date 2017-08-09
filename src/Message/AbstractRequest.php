<?php

namespace Omnipay\GoCardlessV2\Message;

use GoCardlessPro\Client as GoCardlessClient;
use GoCardlessPro\Core\Exception\GoCardlessProException;
use Guzzle\Http\ClientInterface;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

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
     */
    public function send()
    {
        try {
            return $this->sendData($this->getData());
        } catch (GoCardlessProException $e) {
            if ($e->getMessage() == 'Rate limit exceeded') {
                sleep(60);
                try {
                    return $this->sendData($this->getData());
                } catch (GoCardlessProException $f) {
                    $e = $f;
                }
            }
            $response = new ErrorResponse($this, $e);

            return $response;
        } catch (\Exception $e) {
            $response = new ErrorResponse($this, $e);

            return $response;
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
        $response = [
            'account_holder_name' => $this->getAccountHolderName(),
            'account_number' => $this->getAccountNumber(),
            'bank_code' => $this->getBankCode(),
            'branch_code' => $this->getBankBranchCode(),
            'country_code' => $this->getBankCountryCode(),
            'currency' => $this->getCurrency(),
            'iban' => $this->getIban(),
            'metadata' => $this->getBankAccountMetaData(), ];
// Remove null values
        $response = array_filter(
            $response,
            function ($value) {
                return !is_null($value);
            }
        );

        return $response;
    }

    public function setIban($value)
    {
        return $this->setParameter('iban', $value);
    }

    public function getIban()
    {
        return $this->normaliseMetaData($this->getParameter('iban'));
    }

    public function setBankCountryCode($value)
    {
        return $this->setParameter('bankCountryCode', $value);
    }

    public function getBankCountryCode()
    {
        return $this->normaliseMetaData($this->getParameter('bankCountryCode'));
    }

    public function setBankBranchCode($value)
    {
        return $this->setParameter('bankBranchCode', $value);
    }

    public function getBankBranchCode()
    {
        return $this->normaliseMetaData($this->getParameter('bankBranchCode'));
    }

    public function setBankCode($value)
    {
        return $this->setParameter('bankCode', $value);
    }

    public function getBankCode()
    {
        return $this->normaliseMetaData($this->getParameter('bankCode'));
    }

    public function setAccountNumber($value)
    {
        return $this->setParameter('bankAccountNumber', $value);
    }

    public function getAccountNumber()
    {
        return $this->normaliseMetaData($this->getParameter('bankAccountNumber'));
    }

    public function setAccountHolderName($value)
    {
        return $this->setParameter('accountHolderName', $value);
    }

    public function getAccountHolderName()
    {
        return $this->normaliseMetaData($this->getParameter('accountHolderName'));
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
        return $this->normaliseMetaData($this->getParameter('paymentMetaData'));
    }

    public function setSubscriptionMetaData(array $value)
    {
        return $this->setParameter('subscriptionMetaData', $value);
    }

    public function getSubscriptionMetaData()
    {
        return $this->normaliseMetaData($this->getParameter('subscriptionMetaData'));
    }

    public function setCustomerMetaData(array $value)
    {
        return $this->setParameter('customerMetaData', $value);
    }

    public function getCustomerMetaData()
    {
        return $this->normaliseMetaData($this->getParameter('customerMetaData'));
    }

    public function setBankAccountMetaData(array $value)
    {
        return $this->setParameter('bankAccountMetaData', $value);
    }

    public function getBankAccountMetaData()
    {
        return $this->normaliseMetaData($this->getParameter('bankAccountMetaData'));
    }

    private function normaliseMetaData($metaData)
    {
        if (is_array($metaData)) {
            foreach ($metaData as $k => $v) {
                $metaData[$k] = (string) $v;
            }
        }

        return $metaData;
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

    public function setSubscriptionDayOfMonth($value = null)
    {
        // This block can be removed once PHP7 scalar type declarations are available
        if (!is_null($value)) {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException('Subscription day of month must be be null or numeric');
            } else {
                $value = (int) $value;
            }
        }

        /*
         * GoCardless don't allow subscriptions to be set for the 29th, 30th, or 31st of the month, so map these to a
         * special "-1" value which means "last working day of the month".
         */
        if (!is_null($value) && $value > 28) {
            $value = -1;
        }

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
     *
     * @return BaseAbstractRequest
     *
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
