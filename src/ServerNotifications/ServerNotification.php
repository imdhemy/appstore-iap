<?php


namespace Imdhemy\AppStore\ServerNotifications;

use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ValueObjects\Time;

class ServerNotification
{
    const CANCEL = 'CANCEL';
    const DID_CHANGE_RENEWAL_PREF = 'DID_CHANGE_RENEWAL_PREF';
    const DID_CHANGE_RENEWAL_STATUS = 'DID_CHANGE_RENEWAL_STATUS';
    const DID_FAIL_TO_RENEW = 'DID_FAIL_TO_RENEW';
    const DID_RECOVER = 'DID_RECOVER';
    const DID_RENEW = 'DID_RENEW';
    const INITIAL_BUY = 'INITIAL_BUY';
    const INTERACTIVE_RENEWAL = 'INTERACTIVE_RENEWAL';
    const PRICE_INCREASE_CONSENT = 'PRICE_INCREASE_CONSENT';
    const REFUND = 'REFUND';
    const REVOKE = 'REVOKE';

    /**
     * @var ReceiptResponse
     */
    protected $unifiedReceipt;

    /**
     * @var Time|null
     */
    protected $autoRenewStatusChangeDate;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var bool
     */
    protected $autoRenewStatus;

    /**
     * @var string
     */
    protected $bvrs;

    /**
     * @var string
     */
    protected $bid;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $autoRenewProductId;

    /**
     * @var string
     */
    protected $notificationType;

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        $obj = new self();
        $obj->unifiedReceipt = new ReceiptResponse($attributes['unified_receipt']);
        $obj->autoRenewStatusChangeDate = isset($attributes['auto_renew_status_change_date_ms']) ? new Time(
            $attributes['auto_renew_status_change_date_ms']
        ) : null;
        $obj->environment = $attributes['environment'];
        $obj->autoRenewStatus = $attributes['auto_renew_status'] === "true";
        $obj->bvrs = $attributes['bvrs'];
        $obj->bid = $attributes['bid'];
        $obj->password = $attributes['password'];
        $obj->autoRenewProductId = $attributes['auto_renew_product_id'];
        $obj->notificationType = $attributes['notification_type'];

        return $obj;
    }

    /**
     * @return ReceiptResponse
     */
    public function getUnifiedReceipt(): ReceiptResponse
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
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return bool
     */
    public function isAutoRenewStatus(): bool
    {
        return $this->autoRenewStatus;
    }

    /**
     * @return string
     */
    public function getBvrs(): string
    {
        return $this->bvrs;
    }

    /**
     * @return string
     */
    public function getBid(): string
    {
        return $this->bid;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getAutoRenewProductId(): string
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
}
