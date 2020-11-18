<?php


namespace Imdhemy\AppStore\Receipts;

use Imdhemy\AppStore\Receipts\Contracts\ReceiptResponseContract;
use Imdhemy\AppStore\ValueObjects\PendingRenewal;
use Imdhemy\AppStore\ValueObjects\Receipt;
use Imdhemy\AppStore\ValueObjects\ReceiptInfo;
use Imdhemy\AppStore\ValueObjects\Status;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ReceiptResponse
 * @package Imdhemy\AppStore\Receipts
 */
class ReceiptResponse implements ReceiptResponseContract
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
     * @var string
     */
    protected $latestReceipt;

    /**
     * @var array
     */
    protected $latestReceiptInfo;

    /**
     * @var array|null
     */
    protected $pendingRenewalInfo;

    /**
     * @var array
     */
    protected $receipt;

    /**
     * @var int
     */
    protected $status;

    /**
     * ReceiptResponse constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $body = json_decode((string)$response->getBody(), true);
        $this->environment = $body['environment'];
        $this->latestReceipt = $body['latest_receipt'];
        $this->latestReceiptInfo = $body['latest_receipt_info'];
        $this->receipt = $body['receipt'];
        $this->status = $body['status'];

        $this->pendingRenewalInfo = $body['pending_renewal_info'] ?? null;
        $this->isRetryable = $body['is-retryable'] ?? null;
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
    public function isRetryAble(): bool
    {
        return $this->isRetryable;
    }

    /**
     * @return string
     */
    public function getLatestReceipt(): string
    {
        return $this->latestReceipt;
    }

    /**
     * @return ReceiptInfo
     */
    public function getLatestReceiptInfo(): ReceiptInfo
    {
        return ReceiptInfo::fromArray($this->latestReceiptInfo);
    }

    /**
     * @return PendingRenewal
     */
    public function getPendingRenewalInfo(): PendingRenewal
    {
        return PendingRenewal::fromArray($this->pendingRenewalInfo);
    }

    /**
     * @return Receipt
     */
    public function getReceipt(): Receipt
    {
        return Receipt::fromArray($this->receipt);
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return new Status($this->status);
    }
}
