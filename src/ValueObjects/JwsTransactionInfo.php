<?php

namespace Imdhemy\AppStore\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use Imdhemy\AppStore\Jws\JsonWebSignature;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Signature;

/**
 * Class JwsTransactionInfo
 * Transaction information, signed by the App Store, in JSON Web Signature (JWS) format.
 *
 * @link https://developer.apple.com/documentation/appstoreservernotifications/jwstransaction
 */
final class JwsTransactionInfo implements JsonWebSignature
{
    public const ENVIRONMENT_SANDBOX = 'Sandbox';
    public const ENVIRONMENT_PRODUCTION = 'Production';

    public const OWNERSHIP_TYPE_FAMILY_SHARED = 'FAMILY_SHARED';
    public const OWNERSHIP_TYPE_PURCHASED = 'PURCHASED';

    public const OFFER_TYPE_INTRODUCTORY = 1;
    public const OFFER_TYPE_PROMOTIONAL = 2;
    public const OFFER_TYPE_SUBSCRIPTION = 3;

    public const REVOCATION_REASON_APP_ISSUE = 1;
    public const REVOCATION_REASON_OTHER = 0;

    public const TYPE_AUTO_RENEWABLE = 'Auto-Renewable Subscription';
    public const TYPE_NON_RENEWING_SUBSCRIPTION = 'Non-Renewing Subscription';
    public const TYPE_NON_CONSUMABLE = 'Non-Consumable';
    public const TYPE_CONSUMABLE = 'Consumable';

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
     * Get list of headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->jws->getHeaders();
    }

    /**
     * Get list of claims
     *
     * @return array
     */
    public function getClaims(): array
    {
        return $this->jws->getClaims();
    }

    /**
     * Get signature
     *
     * @return string
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
    public function getAppAccountToken(): ?string
    {
        return $this->getClaims()['appAccountToken'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getBundleId(): ?string
    {
        return $this->getClaims()['bundleId'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getEnvironment(): ?string
    {
        return $this->getClaims()['environment'] ?? null;
    }

    /**
     * @psalm-suppress DeprecatedClass Retained as a compatibility bridge until the next major release.
     * @psalm-suppress DeprecatedMethod Retained as a compatibility bridge until the next major release.
     */
    public function getExpiresAt(): ?DateTimeImmutable
    {
        $time = $this->getExpiresDate();

        return null === $time ? null : DateTimeImmutable::createFromInterface($time->toCarbon());
    }

    /**
     * @return Time|null
     * @deprecated Use {@see self::getExpiresAt()} instead. Will be removed in the next major release.
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
     */
    public function getExpiresDate(): ?Time
    {
        if (! isset($this->getClaims()['expiresDate'])) {
            return null;
        }

        return new Time($this->getClaims()['expiresDate']);
    }

    /**
     * @return string|null
     */
    public function getInAppOwnershipType(): ?string
    {
        return $this->getClaims()['inAppOwnershipType'] ?? null;
    }

    /**
     * @return bool|null
     */
    public function getIsUpgraded(): ?bool
    {
        return $this->getClaims()['isUpgraded'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getOfferIdentifier(): ?string
    {
        return $this->getClaims()['offerIdentifier'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getOfferType(): ?string
    {
        return $this->getClaims()['offerType'] ?? null;
    }

    /**
     * @psalm-suppress DeprecatedClass Retained as a compatibility bridge until the next major release.
     * @psalm-suppress DeprecatedMethod Retained as a compatibility bridge until the next major release.
     */
    public function getOriginallyPurchasedAt(): ?DateTimeImmutable
    {
        $time = $this->getOriginalPurchaseDate();

        return null === $time ? null : DateTimeImmutable::createFromInterface($time->toCarbon());
    }

    /**
     * @return Time|null
     * @deprecated Use {@see self::getOriginallyPurchasedAt()} instead. Will be removed in the next major release.
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
     */
    public function getOriginalPurchaseDate(): ?Time
    {
        if (! isset($this->getClaims()['originalPurchaseDate'])) {
            return null;
        }

        return new Time($this->getClaims()['originalPurchaseDate']);
    }

    /**
     * @return string|null
     */
    public function getOriginalTransactionId(): ?string
    {
        return $this->getClaims()['originalTransactionId'] ?? null;
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
    public function getPurchasedAt(): ?DateTimeImmutable
    {
        $time = $this->getPurchaseDate();

        return null === $time ? null : DateTimeImmutable::createFromInterface($time->toCarbon());
    }

    /**
     * @return Time|null
     * @deprecated Use {@see self::getPurchasedAt()} instead. Will be removed in the next major release.
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
     */
    public function getPurchaseDate(): ?Time
    {
        if (! isset($this->getClaims()['purchaseDate'])) {
            return null;
        }

        return new Time($this->getClaims()['purchaseDate']);
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->getClaims()['quantity'] ?? null;
    }

    /**
     * @psalm-suppress DeprecatedClass Retained as a compatibility bridge until the next major release.
     * @psalm-suppress DeprecatedMethod Retained as a compatibility bridge until the next major release.
     */
    public function getRevokedAt(): ?DateTimeImmutable
    {
        $time = $this->getRevocationDate();

        return null === $time ? null : DateTimeImmutable::createFromInterface($time->toCarbon());
    }

    /**
     * @return Time|null
     * @deprecated Use {@see self::getRevokedAt()} instead. Will be removed in the next major release.
     * @psalm-suppress DeprecatedClass Retained for backward compatibility until the next major release.
     */
    public function getRevocationDate(): ?Time
    {
        if (! isset($this->getClaims()['revocationDate'])) {
            return null;
        }

        return new Time($this->getClaims()['revocationDate']);
    }

    /**
     * @return int|null
     */
    public function getRevocationReason(): ?int
    {
        return $this->getClaims()['revocationReason'] ?? null;
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
        if (! isset($this->getClaims()['signedDate'])) {
            return null;
        }

        return new Time($this->getClaims()['signedDate']);
    }

    /**
     * @return string|null
     */
    public function getSubscriptionGroupIdentifier(): ?string
    {
        return $this->getClaims()['subscriptionGroupIdentifier'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->getClaims()['transactionId'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->getClaims()['type'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getWebOrderLineItemId(): ?string
    {
        return $this->getClaims()['webOrderLineItemId'] ?? null;
    }
}
