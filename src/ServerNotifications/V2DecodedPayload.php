<?php

namespace Imdhemy\AppStore\ServerNotifications;

use Imdhemy\AppStore\Contracts\Arrayable;
use Imdhemy\AppStore\Jws\JsonWebSignature;
use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\ValueObjects\AppMetadata;
use Imdhemy\AppStore\ValueObjects\JwsRenewalInfo;
use Imdhemy\AppStore\ValueObjects\JwsTransactionInfo;
use Imdhemy\AppStore\ValueObjects\Time;

/**
 * Class V2DecodedPayload
 * A decoded payload containing the version 2 notification data.
 *
 * @see https://developer.apple.com/documentation/appstoreservernotifications/responsebodyv2decodedpayload
 */
final class V2DecodedPayload implements Arrayable
{
    // Types
    public const TYPE_CONSUMPTION_REQUEST = 'CONSUMPTION_REQUEST';
    public const TYPE_DID_CHANGE_RENEWAL_PREF = 'DID_CHANGE_RENEWAL_PREF';
    public const TYPE_DID_CHANGE_RENEWAL_STATUS = 'DID_CHANGE_RENEWAL_STATUS';
    public const TYPE_DID_FAIL_TO_RENEW = 'DID_FAIL_TO_RENEW';
    public const TYPE_DID_RENEW = 'DID_RENEW';
    public const TYPE_EXPIRED = 'EXPIRED';
    public const TYPE_GRACE_PERIOD_EXPIRED = 'GRACE_PERIOD_EXPIRED';
    public const TYPE_OFFER_REDEEMED = 'OFFER_REDEEMED';
    public const TYPE_PRICE_INCREASE = 'PRICE_INCREASE';
    public const TYPE_REFUND = 'REFUND';
    public const TYPE_REFUND_DECLINED = 'REFUND_DECLINED';
    public const TYPE_RENEWAL_EXTENDED = 'RENEWAL_EXTENDED';
    public const TYPE_REVOKE = 'REVOKE';
    public const TYPE_SUBSCRIBED = 'SUBSCRIBED';
    public const TYPE_TEST = 'TEST';

    // Subtypes
    public const SUBTYPE_INITIAL_BUY = 'INITIAL_BUY';
    public const SUBTYPE_RESUBSCRIBE = 'RESUBSCRIBE';
    public const SUBTYPE_DOWNGRADE = 'DOWNGRADE';
    public const SUBTYPE_UPGRADE = 'UPGRADE';
    public const SUBTYPE_AUTO_RENEW_ENABLED = 'AUTO_RENEW_ENABLED';
    public const SUBTYPE_AUTO_RENEW_DISABLED = 'AUTO_RENEW_DISABLED';
    public const SUBTYPE_VOLUNTARY = 'VOLUNTARY';
    public const SUBTYPE_BILLING_RETRY = 'BILLING_RETRY';
    public const SUBTYPE_PRICE_INCREASE = 'PRICE_INCREASE';
    public const SUBTYPE_GRACE_PERIOD = 'GRACE_PERIOD';
    public const SUBTYPE_BILLING_RECOVERY = 'BILLING_RECOVERY';
    public const SUBTYPE_PENDING = 'PENDING';
    public const SUBTYPE_ACCEPTED = 'ACCEPTED';

    /**
     * @var array
     */
    private array $rawData;

    /**
     * Prevent direct instantiation from outside
     */
    private function __construct(array $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Creates a new instance from a Json Web Signature
     *
     * @param JsonWebSignature $jws
     *
     * @return static
     */
    public static function fromJws(JsonWebSignature $jws): self
    {
        return new self($jws->getClaims());
    }

    /**
     * Creates a new instance from a list of claims
     *
     * @param array $claims
     *
     * @return static
     */
    public static function fromArray(array $claims): self
    {
        return new self($claims);
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->rawData;
    }

    /**
     * Gets the notification subtype
     *
     * @return string|null
     */
    public function getSubType(): ?string
    {
        return $this->rawData['subtype'] ?? null;
    }

    /**
     * Gets the notification type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->rawData['notificationType'];
    }

    /**
     * Gets the notification UUID
     *
     * @return string
     */
    public function getNotificationUUID(): string
    {
        return $this->rawData['notificationUUID'];
    }

    /**
     * Gets the notification version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->rawData['version'];
    }

    /**
     * Gets the notification signed date
     *
     * @return Time
     */
    public function getSignedDate(): Time
    {
        return new Time($this->rawData['signedDate']);
    }

    /**
     * Gets app metadata
     *
     * @return AppMetadata
     */
    public function getAppMetadata(): AppMetadata
    {
        return AppMetadata::fromArray($this->rawData['data']);
    }

    /**
     * Gets the renewal information
     *
     * @return JwsRenewalInfo
     */
    public function getRenewalInfo(): JwsRenewalInfo
    {
        return new JwsRenewalInfo(Parser::toJws($this->rawData['data']['signedRenewalInfo']));
    }

    /**
     * Gets the transaction information
     *
     * @return JwsTransactionInfo
     */
    public function getTransactionInfo(): JwsTransactionInfo
    {
        return new JwsTransactionInfo(Parser::toJws($this->rawData['data']['signedTransactionInfo']));
    }
}
