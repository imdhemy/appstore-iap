<?php

namespace Imdhemy\AppStore\Receipts;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;

/**
 * Verifier class
 *
 * @see     https://developer.apple.com/documentation/appstorereceipts/verifyreceipt
 * @package Imdhemy\AppStore;
 *          This class is responsible for handling verification requests
 */
class Verifier
{
    public const int TEST_ENV_CODE = 21007;

    public const string VERIFY_RECEIPT_PATH = '/verifyReceipt';

    protected ClientInterface $client;

    protected string $receiptData;

    protected string $password;

    /**
     * Receipt constructor.
     *
     */
    public function __construct(ClientInterface $client, string $receiptData, string $password)
    {
        $this->client = $client;
        $this->receiptData = $receiptData;
        $this->password = $password;
    }

    /**
     * @throws GuzzleException|InvalidReceiptException
     * @deprecated Use verify() instead - this method will be removed in the next major release
     */
    public function verifyRenewable(?ClientInterface $sandboxClient = null): ReceiptResponse
    {
        return $this->verify(true, $sandboxClient);
    }

    /**
     *
     * @throws GuzzleException|InvalidReceiptException
     */
    public function verify(
        bool $excludeOldTransactions = false,
        ?ClientInterface $sandboxClient = null
    ): ReceiptResponse {
        $responseBody = $this->sendVerifyRequest($excludeOldTransactions);
        $status = $responseBody['status'];

        if ($this->isInvalidReceiptStatus($status)) {
            throw InvalidReceiptException::create($status);
        }

        if ($this->isFromTestEnv($status)) {
            $sandboxClient = $sandboxClient ?? ClientFactory::createForITunesSandbox();
            $responseBody = $this->sendVerifyRequest($excludeOldTransactions, $sandboxClient);
        }

        return ReceiptResponse::fromArray($responseBody);
    }

    /**
     *
     * @throws GuzzleException
     */
    private function sendVerifyRequest(bool $excludeOldTransactions = false, ?ClientInterface $client = null): array
    {
        $client = $client ?? $this->client;
        $options = $this->buildRequestOptions($excludeOldTransactions);
        $response = $client->post(self::VERIFY_RECEIPT_PATH, $options);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     *
     * @return array[]
     */
    private function buildRequestOptions(bool $excludeOldTransactions): array
    {
        return [
            'json' => [
                'receipt-data' => $this->receiptData,
                'password' => $this->password,
                'exclude-old-transactions' => $excludeOldTransactions,
            ],
        ];
    }

    private function isInvalidReceiptStatus(int $status): bool
    {
        if ($status === self::TEST_ENV_CODE) {
            return false;
        }

        return array_key_exists($status, InvalidReceiptException::ERROR_STATUS_MAP);
    }

    private function isFromTestEnv(int $status): bool
    {
        return $status === self::TEST_ENV_CODE;
    }
}
