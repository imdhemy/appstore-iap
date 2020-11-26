<?php


namespace Imdhemy\AppStore\Receipts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Verifier
{
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
     * @throws GuzzleException
     */
    public function verify(bool $excludeOldTransactions = false): ReceiptResponse
    {
        $options = [
            'json' => [
                'receipt-data' => $this->receiptData,
                'password' => $this->password,
                'exclude-old-transactions' => $excludeOldTransactions,
            ],
        ];
        $response = $this->client->post('/verifyReceipt', $options);

        return new ReceiptResponse(json_decode((string)$response->getBody(), true));
    }

    /**
     * @return ReceiptResponse
     * @throws GuzzleException
     */
    public function verifyRenewable(): ReceiptResponse
    {
        return $this->verify(true);
    }
}
