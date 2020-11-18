<?php


namespace Imdhemy\AppStore\ValueObjects;

final class ReceiptInfo
{
    /**
     * @var Time
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
     * @var string
     */
    private $subscriptionGroupIdentier;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $webOrderLineItemId;

    /**
     * @var int|null
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
        $receiptInfo->expiresDate = new Time($attributes['expires_date_ms']);
        $receiptInfo->webOrderLineItemId = $attributes['web_order_line_item_id'];
        $receiptInfo->isTrialPeriod = strtolower($attributes['is_trial_period']) === "true";
        $receiptInfo->isInIntroOfferPeriod = strtolower($attributes['is_in_intro_offer_period']) === "true";
        $receiptInfo->subscriptionGroupIdentier = $attributes['subscription_group_identifier'];

        $receiptInfo->cancellationDate = isset($attributes['cancellation_date_ms']) ? new Time($attributes['cancellation_date_ms']) : null;
        $receiptInfo->cancellationReason = $attributes['cancellation_reason'] ?? -1;
        $receiptInfo->offerCodeRefName = $attributes['offer_code_ref_name'] ?? null;
        $receiptInfo->promotionalOfferId = $attributes['promotional_offer_id'] ?? null;

        return $receiptInfo;
    }
}
