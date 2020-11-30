<?php


namespace Imdhemy\AppStore\ValueObjects;

final class ReceiptInfo
{
    /**
     * @var Time|null
     */
    private $expiresDate;

    /**
     * @var bool
     */
    private $isInIntroOfferPeriod;

    /**
     * @var bool
     */
    private $isTrialPeriod;

    /**
     * @var bool
     */
    private $isUpgraded;

    /**
     * @var Time
     */
    private $originalPurchaseDate;

    /**
     * @var string
     */
    private $originalTransactionId;

    /**
     * @var string
     */
    private $productId;

    /**
     * @var Time
     */
    private $purchaseDate;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string|null
     */
    private $subscriptionGroupIdentifier;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string|null
     */
    private $webOrderLineItemId;

    /**
     * @var Time|null
     */
    private $cancellationDate;

    /**
     * @var int
     */
    private $cancellationReason;

    /**
     * @var string|null
     */
    private $promotionalOfferId;

    /**
     * @var string|null
     */
    private $offerCodeRefName;

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        $receiptInfo = new self();
        $receiptInfo->quantity = intval($attributes['quantity']);
        $receiptInfo->productId = $attributes['product_id'];
        $receiptInfo->transactionId = $attributes['transaction_id'];
        $receiptInfo->originalTransactionId = $attributes['original_transaction_id'];
        $receiptInfo->purchaseDate = new Time($attributes['purchase_date_ms']);
        $receiptInfo->originalPurchaseDate = new Time($attributes['original_purchase_date_ms']);
        $receiptInfo->expiresDate = isset($attributes['expires_date_ms']) ? new Time($attributes['expires_date_ms'])
            : null;
        $receiptInfo->webOrderLineItemId = $attributes['web_order_line_item_id'] ?? null;
        $receiptInfo->isTrialPeriod = strtolower($attributes['is_trial_period']) === "true";
        $receiptInfo->isInIntroOfferPeriod = isset($attributes['is_in_intro_offer_period']) && strtolower($attributes['is_in_intro_offer_period']) === "true";
        $receiptInfo->subscriptionGroupIdentifier = $attributes['subscription_group_identifier'] ?? null;

        $receiptInfo->cancellationDate = isset($attributes['cancellation_date_ms']) ? new Time($attributes['cancellation_date_ms']) : null;
        $receiptInfo->cancellationReason = $attributes['cancellation_reason'] ?? -1;
        $receiptInfo->offerCodeRefName = $attributes['offer_code_ref_name'] ?? null;
        $receiptInfo->promotionalOfferId = $attributes['promotional_offer_id'] ?? null;
        $receiptInfo->isUpgraded = isset($attributes['is_upgraded']) && $attributes['is_upgraded'] === "true";

        return $receiptInfo;
    }

    /**
     * @return Time|null
     */
    public function getExpiresDate(): ?Time
    {
        TODO:// Throw product does not have an expiration date exception
        return $this->expiresDate;
    }

    /**
     * @return bool
     */
    public function isInIntroOfferPeriod(): bool
    {
        return $this->isInIntroOfferPeriod;
    }

    /**
     * @return bool
     */
    public function isTrialPeriod(): bool
    {
        return $this->isTrialPeriod;
    }

    /**
     * @return bool
     */
    public function isUpgraded(): bool
    {
        return $this->isUpgraded;
    }

    /**
     * @return Time
     */
    public function getOriginalPurchaseDate(): Time
    {
        return $this->originalPurchaseDate;
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
     * @return Time
     */
    public function getPurchaseDate(): Time
    {
        return $this->purchaseDate;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string|null
     */
    public function getSubscriptionGroupIdentifier(): ?string
    {
        return $this->subscriptionGroupIdentifier;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @return string|null
     */
    public function getWebOrderLineItemId(): ?string
    {
        return $this->webOrderLineItemId;
    }

    /**
     * @return Time|null
     */
    public function getCancellationDate(): ?Time
    {
        return $this->cancellationDate;
    }

    /**
     * @return int
     */
    public function getCancellationReason(): int
    {
        return $this->cancellationReason;
    }

    /**
     * @return string|null
     */
    public function getPromotionalOfferId(): ?string
    {
        return $this->promotionalOfferId;
    }

    /**
     * @return string|null
     */
    public function getOfferCodeRefName(): ?string
    {
        return $this->offerCodeRefName;
    }

    /**
     * @return Cancellation
     */
    public function getCancellation(): Cancellation
    {
        return new Cancellation($this->cancellationDate, $this->cancellationReason);
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return ! is_null($this->cancellationDate);
    }
}
