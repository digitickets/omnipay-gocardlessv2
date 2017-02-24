<?php

namespace Omnipay\GoCardlessV2\Message;

use Braintree_Gateway;
use Guzzle\Http\ClientInterface;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use GoCardlessPro\Client as GoCardlessClient;

/**
 * Abstract Request
 *
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    CONST LIVE_OAUTH_URL = 'https://connect.gocardless.com/oauth';
    CONST TEST_OAUTH_URL = 'https://connect-sandbox.gocardless.com/oauth';

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
     * @throws InvalidRequestException
     */
    public function send()
    {
        $this->configure();
        try {
            return $this->sendData($this->getData());
        } catch (\GoCardlessPro\Core\Exception\GoCardlessProException $e) {
            throw new InvalidRequestException($e->getMessage(), $e->getCode(), $e);
        }
    }


    public function configure()
    {
        if ($this->braintree) {
            // When in testMode, use the sandbox environment
            if ($this->getTestMode()) {
                $this->braintree->config->environment('sandbox');
            } else {
                $this->braintree->config->environment('production');
            }

            // Set the keys
            $this->braintree->config->merchantId($this->getMerchantId());
            $this->braintree->config->publicKey($this->getPublicKey());
            $this->braintree->config->privateKey($this->getPrivateKey());
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

    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function getBillingAddressId()
    {
        return $this->getParameter('billingAddressId');
    }

    public function setBillingAddressId($value)
    {
        return $this->setParameter('billingAddressId', $value);
    }

    public function getChannel()
    {
        return $this->getParameter('channel');
    }

    public function setChannel($value)
    {
        return $this->setParameter('channel', $value);
    }

    public function getCustomFields()
    {
        return $this->getParameter('customFields');
    }

    public function setCustomFields($value)
    {
        return $this->setParameter('customFields', $value);
    }

    public function getCustomerData()
    {
        return $this->getParameter('customerData');
    }

    /**
     * @param array $value
     *  [
     *      'address_line1',
     *      'address_line2',
     *      'address_line3',
     *      'city',
     *      'company_name',
     *      'country_code', // iso 3166-1 alpha-2
     *      'email',
     *      'family_name',
     *      'given_name',
     *      'language',  // en / fr / de / pt / es / it / nl / sv
     *      'metadata', //array of up to 3 fields of key (Char 50) : value (Char 500)
     *      'postal_code',
     *      'region',
     *      'swedish_identity_number' //only for autogiro
     *  ]
     *
     * @return BaseAbstractRequest
     */
    public function setCustomerData(array $value)
    {
        return $this->setParameter('customerData', $value);
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

    public function getCustomerBankAccountId()
    {
        return $this->getParameter('customerBankAccountId');
    }

    public function setCustomerBankAccountId($value)
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

    public function getMandateId()
    {
        return $this->getParameter('mandateId');
    }

    public function setMandateId($value)
    {
        return $this->setParameter('mandateId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
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

    public function getTotalRefundedAmount()
    {
        return $this->getParameter('totalRefundedAmount');
    }

    public function setTotalRefundedAmount($value)
    {
        return $this->setParameter('totalRefundedAmount', $value);
    }


    public function getDeviceData()
    {
        return $this->getParameter('deviceData');
    }

    public function setDeviceData($value)
    {
        return $this->setParameter('deviceData', $value);
    }

    public function getDeviceSessionId()
    {
        return $this->getParameter('deviceSessionId');
    }

    public function setDeviceSessionId($value)
    {
        return $this->setParameter('deviceSessionId', $value);
    }

    public function getMerchantAccountId()
    {
        return $this->getParameter('merchantAccountId');
    }

    public function setMerchantAccountId($value)
    {
        return $this->setParameter('merchantAccountId', $value);
    }

    public function getRecurring()
    {
        return $this->getParameter('recurring');
    }

    public function setRecurring($value)
    {
        return $this->setParameter('recurring', (bool) $value);
    }

    public function getAddBillingAddressToPaymentMethod()
    {
        return $this->getParameter('addBillingAddressToPaymentMethod');
    }

    public function setAddBillingAddressToPaymentMethod($value)
    {
        return $this->setParameter('addBillingAddressToPaymentMethod', (bool) $value);
    }

    public function getHoldInEscrow()
    {
        return $this->getParameter('holdInEscrow');
    }

    public function setHoldInEscrow($value)
    {
        return $this->setParameter('holdInEscrow', (bool) $value);
    }

    public function getServiceFeeAmount()
    {
        $amount = $this->getParameter('serviceFeeAmount');
        if ($amount !== null) {
            if (!is_float($amount) &&
                $this->getCurrencyDecimalPlaces() > 0 &&
                false === strpos((string) $amount, '.')
            ) {
                throw new InvalidRequestException(
                    'Please specify amount as a string or float, '.
                    'with decimal places (e.g. \'10.00\' to represent $10.00).'
                );
            }

            return $this->formatCurrency($amount);
        }
    }

    public function setServiceFeeAmount($value)
    {
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

    public function getReference()
    {
        return $this->getParameter('paymentReference');
    }

    public function setReference($value)
    {
        return $this->setParameter('paymentReference', $value);
    }

    public function getSubscriptionInterval()
    {
        return $this->getParameter('subscriptionInterval');
    }

    public function setSubscriptionInterval($value)
    {
        return $this->setParameter('subscriptionInterval', $value);
    }

    public function getSubscriptionDayOfMonth()
    {
        return $this->getParameter('subscriptionDayOfMonth');
    }

    public function setSubscriptionDayOfMonth($value)
    {
        return $this->setParameter('subscriptionDayOfMonth', $value);
    }

    public function getSubscriptionIntervalUnit()
    {
        return $this->getParameter('subscriptionIntervalUnit');
    }

    public function setSubscriptionIntervalUnit($value)
    {
        return $this->setParameter('subscriptionIntervalUnit', $value);
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

    public function getStoreInVault()
    {
        return $this->getParameter('storeInVault');
    }

    public function setStoreInVault($value)
    {
        return $this->setParameter('storeInVault', (bool) $value);
    }

    public function getStoreInVaultOnSuccess()
    {
        return $this->getParameter('storeInVaultOnSuccess');
    }

    public function setStoreInVaultOnSuccess($value)
    {
        return $this->setParameter('storeInVaultOnSuccess', (bool) $value);
    }

    public function getStoreShippingAddressInVault()
    {
        return $this->getParameter('storeShippingAddressInVault');
    }

    public function setStoreShippingAddressInVault($value)
    {
        return $this->setParameter('storeShippingAddressInVault', (bool) $value);
    }

    public function getShippingAddressId()
    {
        return $this->getParameter('shippingAddressId');
    }

    public function setShippingAddressId($value)
    {
        return $this->setParameter('shippingAddressId', $value);
    }

    public function getPurchaseOrderNumber()
    {
        return $this->getParameter('purchaseOrderNumber');
    }

    public function setPurchaseOrderNumber($value)
    {
        return $this->setParameter('purchaseOrderNumber', $value);
    }

    public function getTaxAmount()
    {
        return $this->getParameter('taxAmount');
    }

    public function setTaxAmount($value)
    {
        return $this->setParameter('taxAmount', $value);
    }

    public function getTaxExempt()
    {
        return $this->getParameter('taxExempt');
    }

    public function setTaxExempt($value)
    {
        return $this->setParameter('taxExempt', (bool) $value);
    }

    public function getPaymentMethodToken()
    {
        return $this->getParameter('paymentMethodToken');
    }

    public function setPaymentMethodToken($value)
    {
        return $this->setParameter('paymentMethodToken', $value);
    }

    public function getPaymentMethodNonce()
    {
        return $this->getToken();
    }

    public function setPaymentMethodNonce($value)
    {
        return $this->setToken($value);
    }

    public function getFailOnDuplicatePaymentMethod()
    {
        return $this->getParameter('failOnDuplicatePaymentMethod');
    }

    public function setFailOnDuplicatePaymentMethod($value)
    {
        return $this->setParameter('failOnDuplicatePaymentMethod', (bool) $value);
    }

    public function getMakeDefault()
    {
        return $this->getParameter('makeDefault');
    }

    public function setMakeDefault($value)
    {
        return $this->setParameter('makeDefault', (bool) $value);
    }

    public function getVerifyCard()
    {
        return $this->getParameter('verifyCard');
    }

    public function setVerifyCard($value)
    {
        return $this->setParameter('verifyCard', (bool) $value);
    }

    public function getVerificationMerchantAccountId()
    {
        return $this->getParameter('verificationMerchantAccountId');
    }

    public function setVerificationMerchantAccountId($value)
    {
        return $this->setParameter('verificationMerchantAccountId', $value);
    }
}
