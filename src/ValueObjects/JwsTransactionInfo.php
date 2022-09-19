<?php

namespace Imdhemy\AppStore\ValueObjects;

use Imdhemy\AppStore\Jws\JsonWebSignature;

/**
 * Class JwsTransactionInfo
 * Transaction information, signed by the App Store, in JSON Web Signature (JWS) format.
 *
 * @link https://developer.apple.com/documentation/appstoreservernotifications/jwstransaction
 */
final class JwsTransactionInfo implements JsonWebSignature
{
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
     * @return Time|null
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
     * @return Time|null
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
     * @return Time|null
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
     * @return Time|null
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
     * @return Time|null
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
