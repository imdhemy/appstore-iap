<?php


namespace Imdhemy\AppStore\ValueObjects;

final class PendingRenewal
{
    /**
     * @var string
     */
    private $autoRenewProductId;

    /**
     * @var bool
     */
    private $autoRenewStatus;

    /**
     * @var bool
     */
    private $isInBillingRetryPeriod;

    /**
     * @var string
     */
    private $originalTransactionId;

    /**
     * @var string
     */
    private $productId;

    /**
     * @var bool
     */
    private $priceConsentStatus;

    /**
     * @var Time|null
     */
    private $gracePeriodExpiresDate;

    /**
     * @var string|null
     */
    private $offerCodeRefName;

    /**
     * @var ExpirationIntent|null
     */
    private $expirationIntent;

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        $obj = new self();

        $obj->autoRenewProductId = $attributes['auto_renew_product_id'];
        $obj->autoRenewStatus = (int)$attributes['auto_renew_status'] === 1;
        $obj->isInBillingRetryPeriod = isset($attributes['is_in_billing_retry_period']) && (int)$attributes['is_in_billing_retry_period'] === 1;
        $obj->originalTransactionId = $attributes['original_transaction_id'];
        $obj->productId = $attributes['product_id'];

        $obj->priceConsentStatus = isset($attributes['price_consent_status']) && (int)$attributes['price_consent_status'] === 1;
        $obj->expirationIntent = isset($attributes['expiration_intent']) ? new ExpirationIntent($attributes['expiration_intent']) : null;
        $obj->offerCodeRefName = $attributes['offer_code_ref_name'] ?? null;
        $obj->gracePeriodExpiresDate = isset($attributes['grace_period_expires_date_ms']) ? new Time($attributes['grace_period_expires_date_ms']) : null;

        return $obj;
    }

    /**
     * @return string
     */
    public function getAutoRenewProductId(): string
    {
        return $this->autoRenewProductId;
    }

    /**
     * @return bool
     */
    public function isAutoRenewStatus(): bool
    {
        return $this->autoRenewStatus;
    }

    /**
     * @return bool
     */
    public function isInBillingRetryPeriod(): bool
    {
        return $this->isInBillingRetryPeriod;
    }

    /**
     * @return string
     */
    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return bool
     */
    public function isPriceConsentStatus(): bool
    {
        return $this->priceConsentStatus;
    }

    /**
     * @return Time|null
     */
    public function getGracePeriodExpiresDate(): ?Time
    {
        return $this->gracePeriodExpiresDate;
    }

    /**
     * @return string|null
     */
    public function getOfferCodeRefName(): ?string
    {
        return $this->offerCodeRefName;
    }

    /**
     * @return ExpirationIntent|null
     */
    public function getExpirationIntent(): ?ExpirationIntent
    {
        return $this->expirationIntent;
    }
}
