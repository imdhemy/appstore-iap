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
     * @var string
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
     * @var Receipt
     */
    protected $receipt;

    /**
     * @var Status
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

        $this->latestReceiptInfo = [];
        foreach ($body['latest_receipt_info'] as $itemAttributes) {
            $this->latestReceiptInfo = ReceiptInfo::fromArray($itemAttributes);
        }

        $this->receipt = Receipt::fromArray($body['receipt']);
        $this->status = new Status($body['status']);

        $body['pending_renewal_info'] = $body['pending_renewal_info'] ?? [];
        $this->pendingRenewalInfo = [];
        foreach ($body['pending_renewal_info'] as $item) {
            $this->pendingRenewalInfo[] = PendingRenewal::fromArray($item);
        }

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
     * @return bool|null
     */
    public function getIsRetryable(): ?bool
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
     * @return array|ReceiptInfo[]
     */
    public function getLatestReceiptInfo()
    {
        return $this->latestReceiptInfo;
    }

    /**
     * @return array|PendingRenewal[]
     */
    public function getPendingRenewalInfo()
    {
        return $this->pendingRenewalInfo;
    }

    /**
     * @return Receipt
     */
    public function getReceipt(): Receipt
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
