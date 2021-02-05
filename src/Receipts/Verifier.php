<?php


namespace Imdhemy\AppStore\Receipts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;

class Verifier
{
    const TEST_ENV_CODE = 21007;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    private $receiptData;

    /**
     * @var string
     */
    private $password;

    /**
     * Receipt constructor.
     * @param Client $client
     * @param string $receiptData
     * @param string $password
     */
    public function __construct(Client $client, string $receiptData, string $password)
    {
        $this->client = $client;
        $this->receiptData = $receiptData;
        $this->password = $password;
    }

    /**
     * @param bool $excludeOldTransactions
     * @return ReceiptResponse
     * @throws GuzzleException|InvalidReceiptException
     */
    public function verify(bool $excludeOldTransactions = false): ReceiptResponse
    {
        $responseBody = $this->sendVerifyRequest($excludeOldTransactions);

        $status = $responseBody['status'];

        if ($this->isFromTestEnv($status)) {
            $this->client = ClientFactory::createSandbox();
            $responseBody = $this->sendVerifyRequest($excludeOldTransactions);
        }

        return new ReceiptResponse($responseBody);
    }

    /**
     * @return ReceiptResponse
     * @throws GuzzleException|InvalidReceiptException
     */
    public function verifyRenewable(): ReceiptResponse
    {
        return $this->verify(true);
    }

    /**
     * @param bool $excludeOldTransactions
     * @return array
     * @throws GuzzleException
     */
    protected function sendVerifyRequest(bool $excludeOldTransactions = false): array
    {
        $options = $this->buildRequestOptions($excludeOldTransactions);
        $response = $this->client->post('/verifyReceipt', $options);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @param bool $excludeOldTransactions
     * @return array[]
     */
    protected function buildRequestOptions(bool $excludeOldTransactions): array
    {
        return [
            'json' => [
                'receipt-data' => $this->receiptData,
                'password' => $this->password,
                'exclude-old-transactions' => $excludeOldTransactions,
            ],
        ];
    }

    /**
     * @param int $status
     * @return bool
     */
    protected function isFromTestEnv(int $status): bool
    {
        return $status === self::TEST_ENV_CODE;
    }
}
