<?php


namespace Imdhemy\AppStore\ValueObjects;

final class Receipt
{
    /**
     * @var int
     */
    private $adamId;

    /**
     * @var int
     */
    private $appItemId;

    /**
     * @var string
     */
    private $applicationVersion;

    /**
     * @var string
     */
    private $bundleId;

    /**
     * @var int
     */
    private $downloadId;

    /**
     * @var Time|null
     */
    private $expirationDate;

    /**
     * @var array|ReceiptInfo[]
     */
    private $inApp;

    /**
     * @var string|null
     */
    private $originalApplicationVersion;

    /**
     * @var Time
     */
    private $originalPurchaseDate;

    /**
     * @var Time|null
     */
    private $preOrderDate;

    /**
     * @var Time
     */
    private $receiptCreationDate;

    /**
     * @var string
     */
    private $receiptType;

    /**
     * @var Time
     */
    private $requestDate;

    /**
     * @var int
     */
    private $versionExternalIdentifier;

    /**
     * @param array $attributes
     * @return static
     */
    public static function fromArray(array $attributes): self
    {
        $obj = new self();

        $obj->adamId = $attributes['adam_id'];
        $obj->applicationVersion = $attributes['application_version'];
        $obj->bundleId = $attributes['bundle_id'];
        $obj->downloadId = $attributes['download_id'];
        $obj->expirationDate = isset($attributes['expiration_date_ms']) ? new Time($attributes['expiration_date_ms']) :
            null;
        $obj->inApp = [];
        foreach ($attributes['in_app'] as $receiptAttributes) {
            $obj->inApp[] = ReceiptInfo::fromArray($receiptAttributes);
        }
        $obj->originalApplicationVersion = $attributes['original_application_version'] ?? null;
        $obj->originalPurchaseDate = new Time($attributes['original_purchase_date_ms']);
        $obj->preOrderDate = isset($attributes['preorder_date_ms']) ? new Time($attributes['preorder_date_ms']) : null;
        $obj->receiptCreationDate = new Time($attributes['receipt_creation_date_ms']);
        $obj->receiptType = $attributes['receipt_type'];
        $obj->requestDate = $attributes['request_date_ms'];
        $obj->versionExternalIdentifier = $attributes['version_external_identifier'];

        return $obj;
    }

    /**
     * @return int
     */
    public function getAdamId(): int
    {
        return $this->adamId;
    }

    /**
     * @return int
     */
    public function getAppItemId(): int
    {
        return $this->appItemId;
    }

    /**
     * @return string
     */
    public function getApplicationVersion(): string
    {
        return $this->applicationVersion;
    }

    /**
     * @return string
     */
    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    /**
     * @return int
     */
    public function getDownloadId(): int
    {
        return $this->downloadId;
    }

    /**
     * @return Time|null
     */
    public function getExpirationDate(): ?Time
    {
        return $this->expirationDate;
    }

    /**
     * @return array
     */
    public function getInApp(): array
    {
        return $this->inApp;
    }

    /**
     * @return string|null
     */
    public function getOriginalApplicationVersion(): ?string
    {
        return $this->originalApplicationVersion;
    }

    /**
     * @return Time
     */
    public function getOriginalPurchaseDate(): Time
    {
        return $this->originalPurchaseDate;
    }

    /**
     * @return Time|null
     */
    public function getPreOrderDate(): ?Time
    {
        return $this->preOrderDate;
    }

    /**
     * @return Time
     */
    public function getReceiptCreationDate(): Time
    {
        return $this->receiptCreationDate;
    }

    /**
     * @return string
     */
    public function getReceiptType(): string
    {
        return $this->receiptType;
    }

    /**
     * @return Time
     */
    public function getRequestDate(): Time
    {
        return $this->requestDate;
    }

    /**
     * @return int
     */
    public function getVersionExternalIdentifier(): int
    {
        return $this->versionExternalIdentifier;
    }
}
