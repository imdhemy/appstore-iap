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
    public const string CANCEL = 'CANCEL';
    public const string CONSUMPTION_REQUEST = 'CONSUMPTION_REQUEST';
    public const string DID_CHANGE_RENEWAL_PREF = 'DID_CHANGE_RENEWAL_PREF';
    public const string DID_CHANGE_RENEWAL_STATUS = 'DID_CHANGE_RENEWAL_STATUS';
    public const string DID_FAIL_TO_RENEW = 'DID_FAIL_TO_RENEW';
    public const string DID_RECOVER = 'DID_RECOVER';
    public const string DID_RENEW = 'DID_RENEW';
    public const string INITIAL_BUY = 'INITIAL_BUY';
    public const string INTERACTIVE_RENEWAL = 'INTERACTIVE_RENEWAL';
    public const string PRICE_INCREASE_CONSENT = 'PRICE_INCREASE_CONSENT';
    public const string REFUND = 'REFUND';
    public const string REVOKE = 'REVOKE';
    public const string ONE_TIME_CHARGE = 'ONE_TIME_CHARGE';

    protected ?ReceiptResponse $unifiedReceipt;

    protected ?Time $autoRenewStatusChangeDate;

    protected ?string $environment;

    protected ?bool $autoRenewStatus;

    protected ?string $bvrs;

    protected ?string $bid;

    protected ?string $password;

    protected ?string $autoRenewProductId;

    protected string $notificationType;

    private array $rawData;

    /**
     * @deprecated Use ServerNotification::fromArray() instead
     */
    public function __construct(string $notificationType)
    {
        $this->notificationType = $notificationType;
    }

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
            'true' === $attributes['auto_renew_status'] || true === $attributes['auto_renew_status'] :
            null;

        $obj->bvrs = $attributes['bvrs'] ?? null;
        $obj->bid = $attributes['bid'] ?? null;
        $obj->password = $attributes['password'] ?? null;
        $obj->autoRenewProductId = $attributes['auto_renew_product_id'] ?? null;

        return $obj;
    }

    public function getUnifiedReceipt(): ?ReceiptResponse
    {
        return $this->unifiedReceipt;
    }

    public function getAutoRenewStatusChangeDate(): ?Time
    {
        return $this->autoRenewStatusChangeDate;
    }

    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    public function getBvrs(): ?string
    {
        return $this->bvrs;
    }

    public function getBid(): ?string
    {
        return $this->bid;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAutoRenewProductId(): ?string
    {
        return $this->autoRenewProductId;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    public function getAutoRenewStatus(): ?bool
    {
        return $this->autoRenewStatus;
    }

    /**
     * Convert the object to its array representation.
     */
    public function toArray(): array
    {
        return $this->rawData;
    }
}
