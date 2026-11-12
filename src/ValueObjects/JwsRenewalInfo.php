<?php

namespace Imdhemy\AppStore\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use Imdhemy\AppStore\Jws\JsonWebSignature;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Signature;

/**
 * Class JwsRenewalInfo
 * Subscription renewal information signed by the App Store, in JSON Web Signature (JWS) format.
 *
 * @see https://developer.apple.com/documentation/appstoreservernotifications/jwsrenewalinfo
 */
final class JwsRenewalInfo implements JsonWebSignature
{
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
     * Returns the token headers
     *
     * The UnencryptedToken methods below delegate to the wrapped JWS. This
     * class previously used UnEncryptedTokenConcern, whose methods read a
     * $token property that only exists on the Jws class itself, so calling
     * any of them here raised an "Undefined property" error.
     */
    public function headers(): DataSet
    {
        return $this->jws->headers();
    }

    /**
     * Returns if the token is allowed to be used by the audience
     * @param non-empty-string $audience
     */
    public function isPermittedFor(string $audience): bool
    {
        return $this->jws->isPermittedFor($audience);
    }

    /**
     * Returns if the token has the given id
     * @param non-empty-string $id
     */
    public function isIdentifiedBy(string $id): bool
    {
        return $this->jws->isIdentifiedBy($id);
    }

    /**
     * Returns if the token has the given subject
     * @param non-empty-string $subject
     */
    public function isRelatedTo(string $subject): bool
    {
        return $this->jws->isRelatedTo($subject);
    }

    /**
     * Returns if the token was issued by any of given issuers
     */
    public function hasBeenIssuedBy(string ...$issuers): bool
    {
        return $this->jws->hasBeenIssuedBy(...$issuers);
    }

    /**
     * Returns if the token was issued before of given time
     */
    public function hasBeenIssuedBefore(DateTimeInterface $now): bool
    {
        return $this->jws->hasBeenIssuedBefore($now);
    }

    /**
     * Returns if the token minimum time is before than given time
     */
    public function isMinimumTimeBefore(DateTimeInterface $now): bool
    {
        return $this->jws->isMinimumTimeBefore($now);
    }

    /**
     * Returns if the token is expired
     */
    public function isExpired(DateTimeInterface $now): bool
    {
        return $this->jws->isExpired($now);
    }

    /**
     * Returns an encoded representation of the token
     */
    public function toString(): string
    {
        return $this->jws->toString();
    }

    /**
     * Returns the token claims
     */
    public function claims(): DataSet
    {
        return $this->jws->claims();
    }

    /**
     * Returns the token signature
     */
    public function signature(): Signature
    {
        return $this->jws->signature();
    }

    /**
     * Returns the token payload
     */
    public function payload(): string
    {
        return $this->jws->payload();
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
     * @psalm-suppress DeprecatedClass Retained as a compatibility bridge until the next major release.
     * @psalm-suppress DeprecatedMethod Retained as a compatibility bridge until the next major release.
     */
    public function getGracePeriodExpiresAt(): ?DateTimeImmutable
    {
        $time = $this->getGracePeriodExpiresDate();

        return null === $time ? null : DateTimeImmutable::createFromInterface($time->toCarbon());
    }

    /**
     * @return Time|null
     * @deprecated Use {@see self::getGracePeriodExpiresAt()} instead. Will be removed in the next major release.
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
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
     * @psalm-suppress DeprecatedClass Retained as a compatibility bridge until the next major release.
     * @psalm-suppress DeprecatedMethod Retained as a compatibility bridge until the next major release.
     */
    public function getRecentSubscriptionStartedAt(): ?DateTimeImmutable
    {
        $time = $this->getRecentSubscriptionStartDate();

        return null === $time ? null : DateTimeImmutable::createFromInterface($time->toCarbon());
    }

    /**
     * @return Time|null
     * @deprecated Use {@see self::getRecentSubscriptionStartedAt()} instead. Will be removed in the next major release.
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
     */
    public function getRecentSubscriptionStartDate(): ?Time
    {
        if (isset($this->getClaims()['recentSubscriptionStartDate'])) {
            return new Time($this->getClaims()['recentSubscriptionStartDate']);
        }

        return null;
    }

    /**
     * @psalm-suppress DeprecatedClass Retained as a compatibility bridge until the next major release.
     * @psalm-suppress DeprecatedMethod Retained as a compatibility bridge until the next major release.
     */
    public function getSignedAt(): ?DateTimeImmutable
    {
        $time = $this->getSignedDate();

        return null === $time ? null : DateTimeImmutable::createFromInterface($time->toCarbon());
    }

    /**
     * @return Time|null
     * @deprecated Use {@see self::getSignedAt()} instead. Will be removed in the next major release.
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
     */
    public function getSignedDate(): ?Time
    {
        if (isset($this->getClaims()['signedDate'])) {
            return new Time($this->getClaims()['signedDate']);
        }

        return null;
    }
}
