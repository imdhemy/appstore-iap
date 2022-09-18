<?php

namespace Imdhemy\AppStore\ServerNotifications;

use Imdhemy\AppStore\Contracts\Arrayable;
use Imdhemy\AppStore\Jws\JsonWebSignature;

/**
 * Class V2DecodedPayload
 * A decoded payload containing the version 2 notification data.
 *
 * @see https://developer.apple.com/documentation/appstoreservernotifications/responsebodyv2decodedpayload
 */
final class V2DecodedPayload implements Arrayable
{
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

    /**
     * @var string
     */
    private string $notificationType;

    /**
     * @var string
     */
    private string $subType;

    /**
     * @var string
     */
    private string $notificationUUID;

    /**
     * @var array
     */
    private array $data;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var int
     */
    private int $signedDate;

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
     * Create a new instance from a Json Web Signature
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
     * Convert the object to its array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->rawData;
    }
}
