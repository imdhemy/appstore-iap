<?php


namespace Imdhemy\AppStore\Receipts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\Receipts\Contracts\ReceiptContract;
use Imdhemy\AppStore\Receipts\Contracts\ReceiptResponseContract;

class Verifier implements ReceiptContract
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
     * @return ReceiptResponseContract
     * @throws GuzzleException
     */
    public function verify(bool $excludeOldTransactions = false): ReceiptResponseContract
    {
        $options = [
            'json' => [
                'receipt-data' => $this->receiptData,
                'password' => $this->password,
                'exclude-old-transactions' => (string)$excludeOldTransactions,
            ],
        ];
        $response = $this->client->post('/verifyReceipt', $options);

        return new ReceiptResponse($response);
    }

    /**
     * @return ReceiptResponseContract
     * @throws GuzzleException
     */
    public function verifyRenewable(): ReceiptResponseContract
    {
        return $this->verify(true);
    }
}
