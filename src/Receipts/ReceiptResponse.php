<?php


namespace Imdhemy\AppStore\Receipts;

use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\ValueObjects\PendingRenewal;
use Imdhemy\AppStore\ValueObjects\Receipt;
use Imdhemy\AppStore\ValueObjects\ReceiptInfo;
use Imdhemy\AppStore\ValueObjects\Status;

/**
 * Class ReceiptResponse
 * @package Imdhemy\AppStore\Receipts
 */
class ReceiptResponse
{
    /**
     * @var string
     */
    protected $environment;

    /**
     * @var bool|null
     */
    protected $isRetryable;

    /**
     * @var string|null
     */
    protected $latestReceipt;

    /**
     * @var array|ReceiptInfo[]
     */
    protected $latestReceiptInfo;

    /**
     * @var array|PendingRenewal[]
     */
    protected $pendingRenewalInfo;

    /**
     * @var Receipt|null
     */
    protected $receipt;

    /**
     * @var Status
     */
    protected $status;

    /**
     * ReceiptResponse constructor.
     * TODO: replace public constructor usage with a static factory method
     * @param array $attributes
     * @throws InvalidReceiptException
     */
    public function __construct(array $attributes)
    {
        if ($attributes['status'] !== 0) {
            throw InvalidReceiptException::create($attributes['status']);
        }

        $this->environment = $attributes['environment'];
        $this->latestReceipt = $attributes['latest_receipt'] ?? null;

        $this->latestReceiptInfo = [];
        foreach ($attributes['latest_receipt_info'] ?? [] as $itemAttributes) {
            $this->latestReceiptInfo[] = ReceiptInfo::fromArray($itemAttributes);
        }

        $this->receipt = isset($attributes['receipt']) ? Receipt::fromArray($attributes['receipt']) : null;
        $this->status = new Status($attributes['status']);

        $this->pendingRenewalInfo = [];
        foreach ($attributes['pending_renewal_info'] ?? [] as $item) {
            $this->pendingRenewalInfo[] = PendingRenewal::fromArray($item);
        }

        $this->isRetryable = $attributes['is-retryable'] ?? null;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return bool|null
     */
    public function getIsRetryable(): ?bool
    {
        return $this->isRetryable;
    }

    /**
     * @return string
     */
    public function getLatestReceipt(): ?string
    {
        return $this->latestReceipt;
    }

    /**
     * @return array|ReceiptInfo[]
     */
    public function getLatestReceiptInfo(): array
    {
        return $this->latestReceiptInfo;
    }

    /**
     * @return array|PendingRenewal[]
     */
    public function getPendingRenewalInfo(): array
    {
        return $this->pendingRenewalInfo;
    }

    /**
     * @return Receipt|null
     */
    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }
}
