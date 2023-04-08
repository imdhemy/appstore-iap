<?php

namespace Imdhemy\AppStore\ServerNotifications;

use Imdhemy\AppStore\Contracts\Arrayable;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ValueObjects\Time;

/**
 * App Store Server Notifications
 *
 * @see https://developer.apple.com/documentation/appstoreservernotifications?changes=latest_minor
 */
class ServerNotification implements Arrayable
{
    // Notification types
    public const CANCEL = 'CANCEL';
    public const CONSUMPTION_REQUEST = 'CONSUMPTION_REQUEST';
    public const DID_CHANGE_RENEWAL_PREF = 'DID_CHANGE_RENEWAL_PREF';
    public const DID_CHANGE_RENEWAL_STATUS = 'DID_CHANGE_RENEWAL_STATUS';
    public const DID_FAIL_TO_RENEW = 'DID_FAIL_TO_RENEW';
    public const DID_RECOVER = 'DID_RECOVER';
    public const DID_RENEW = 'DID_RENEW';
    public const INITIAL_BUY = 'INITIAL_BUY';
    public const INTERACTIVE_RENEWAL = 'INTERACTIVE_RENEWAL';
    public const PRICE_INCREASE_CONSENT = 'PRICE_INCREASE_CONSENT';
    public const REFUND = 'REFUND';
    public const REVOKE = 'REVOKE';

    /**
     * @var ReceiptResponse|null
     */
    protected ?ReceiptResponse $unifiedReceipt;

    /**
     * @var Time|null
     */
    protected ?Time $autoRenewStatusChangeDate;

    /**
     * @var string|null
     */
    protected ?string $environment;

    /**
     * @var bool|null
     */
    protected ?bool $autoRenewStatus;

    /**
     * @var string|null
     */
    protected ?string $bvrs;

    /**
     * @var string|null
     */
    protected ?string $bid;

    /**
     * @var string|null
     */
    protected ?string $password;

    /**
     * @var string|null
     */
    protected ?string $autoRenewProductId;

    /**
     * @var string
     */
    protected string $notificationType;

    /**
     * @var array
     */
    private array $rawData;

    /**
     * @param string $notificationType
     *
     * @deprecated Use ServerNotification::fromArray() instead
     */
    public function __construct(string $notificationType)
    {
        $this->notificationType = $notificationType;
    }

    /**
     * @param array $attributes
     *
     * @return ServerNotification
     */
    public static function fromArray(array $attributes = []): self
    {
        $obj = new self($attributes['notification_type']);
        $obj->rawData = $attributes;

        $obj->unifiedReceipt = isset($attributes['unified_receipt']) ?
            ReceiptResponse::fromArray($attributes['unified_receipt']) :
            null;

        $obj->autoRenewStatusChangeDate = isset($attributes['auto_renew_status_change_date_ms']) ?
            new Time($attributes['auto_renew_status_change_date_ms']) :
            null;

        $obj->environment = $attributes['environment'] ?? null;

        $obj->autoRenewStatus = isset($attributes['auto_renew_status']) ?
            $attributes['auto_renew_status'] === "true" || $attributes['auto_renew_status'] === true :
            null;

        $obj->bvrs = $attributes['bvrs'] ?? null;
        $obj->bid = $attributes['bid'] ?? null;
        $obj->password = $attributes['password'] ?? null;
        $obj->autoRenewProductId = $attributes['auto_renew_product_id'] ?? null;

        return $obj;
    }

    /**
     * @return ReceiptResponse|null
     */
    public function getUnifiedReceipt(): ?ReceiptResponse
    {
        return $this->unifiedReceipt;
    }

    /**
     * @return Time|null
     */
    public function getAutoRenewStatusChangeDate(): ?Time
    {
        return $this->autoRenewStatusChangeDate;
    }

    /**
     * @return string|null
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * @return string|null
     */
    public function getBvrs(): ?string
    {
        return $this->bvrs;
    }

    /**
     * @return string|null
     */
    public function getBid(): ?string
    {
        return $this->bid;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getAutoRenewProductId(): ?string
    {
        return $this->autoRenewProductId;
    }

    /**
     * @return string
     */
    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    /**
     * @return bool|null
     */
    public function getAutoRenewStatus(): ?bool
    {
        return $this->autoRenewStatus;
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
