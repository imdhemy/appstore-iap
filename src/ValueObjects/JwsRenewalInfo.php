<?php

namespace Imdhemy\AppStore\ValueObjects;

use Imdhemy\AppStore\Jws\JsonWebSignature;
use Imdhemy\AppStore\Jws\UnEncryptedTokenConcern;

/**
 * Class JwsRenewalInfo
 * Subscription renewal information signed by the App Store, in JSON Web Signature (JWS) format.
 *
 * @see https://developer.apple.com/documentation/appstoreservernotifications/jwsrenewalinfo
 */
final class JwsRenewalInfo implements JsonWebSignature
{
    use UnEncryptedTokenConcern;

    public const AUTO_RENEW_STATUS_OFF = 0;

    public const AUTO_RENEW_STATUS_ON = 1;

    public const ENVIRONMENT_PRODUCTION = 'Production';

    public const ENVIRONMENT_SANDBOX = 'Sandbox';

    public const EXPIRATION_INTENT_CANCEL = 1;

    public const EXPIRATION_INTENT_BILLING_ERROR = 2;

    public const EXPIRATION_INTENT_PRICE_INCREASE_CONSENT = 3;

    public const EXPIRATION_INTENT_PRODUCT_NOT_AVAILABLE = 4;

    public const OFFER_TYPE_INTRODUCTORY = 1;

    public const OFFER_TYPE_PROMOTIONAL = 2;

    public const OFFER_TYPE_OFFER_CODE = 3;

    public const PRICE_INCREASE_STATUS_NOT_RESPONDED = 0;

    public const PRICE_INCREASE_STATUS_CONSENT = 1;

    /**
     * @var JsonWebSignature
     */
    private JsonWebSignature $jws;

    /**
     * @param JsonWebSignature $jws
     */
    public function __construct(JsonWebSignature $jws)
    {
        $this->jws = $jws;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->jws->__toString();
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->jws->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function getClaims(): array
    {
        return $this->jws->getClaims();
    }

    /**
     * @inheritDoc
     */
    public function getSignature(): string
    {
        return $this->jws->getSignature();
    }

    /**
     * @return string|null
     */
    public function getAutoRenewProductId(): ?string
    {
        return $this->getClaims()['autoRenewProductId'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getAutoRenewStatus(): ?int
    {
        return $this->getClaims()['autoRenewStatus'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getEnvironment(): ?string
    {
        return $this->getClaims()['environment'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getExpirationIntent(): ?string
    {
        return $this->getClaims()['expirationIntent'] ?? null;
    }

    /**
     * @return Time|null
     */
    public function getGracePeriodExpiresDate(): ?Time
    {
        if (isset($this->getClaims()['gracePeriodExpiresDate'])) {
            return new Time($this->getClaims()['gracePeriodExpiresDate']);
        }

        return null;
    }

    /**
     * @return bool|null
     */
    public function getIsInBillingRetryPeriod(): ?bool
    {
        return $this->getClaims()['isInBillingRetryPeriod'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getOfferIdentifier(): ?string
    {
        return $this->getClaims()['offerIdentifier'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getOfferType(): ?int
    {
        return $this->getClaims()['offerType'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getOriginalTransactionId(): ?string
    {
        return $this->getClaims()['originalTransactionId'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getPriceIncreaseStatus(): ?int
    {
        return $this->getClaims()['priceIncreaseStatus'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getProductId(): ?string
    {
        return $this->getClaims()['productId'] ?? null;
    }

    /**
     * @return Time|null
     */
    public function getRecentSubscriptionStartDate(): ?Time
    {
        if (isset($this->getClaims()['recentSubscriptionStartDate'])) {
            return new Time($this->getClaims()['recentSubscriptionStartDate']);
        }

        return null;
    }

    /**
     * @return Time|null
     */
    public function getSignedDate(): ?Time
    {
        if (isset($this->getClaims()['signedDate'])) {
            return new Time($this->getClaims()['signedDate']);
        }

        return null;
    }
}
