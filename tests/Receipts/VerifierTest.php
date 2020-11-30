<?php

namespace Imdhemy\AppStore\Tests\Receipts;

use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\Receipts\Verifier;
use Imdhemy\AppStore\Tests\TestCase;

class VerifierTest extends TestCase
{
    /**
     * @test
     * @throws GuzzleException
     */
    public function test_verify_subscription()
    {
        // Given
        $iosReceipt = json_decode(file_get_contents(__DIR__ . '/../subscription-receipt.json'), true);
        $client = ClientFactory::createSandbox();
        $password = getenv('PASSWORD');

        $receiptData = $iosReceipt['transactionReceipt'];
        $receipt = new Verifier($client, $receiptData, $password);

        // when
        $response = $receipt->verifyRenewable();

        // then
        $this->assertInstanceOf(ReceiptResponse::class, $response);
    }
}
