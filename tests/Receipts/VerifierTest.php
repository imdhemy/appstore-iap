<?php

namespace Imdhemy\AppStore\Tests\Receipts;

use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Exceptions\InvalidReceiptException;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\Receipts\Verifier;
use Imdhemy\AppStore\Tests\TestCase;

class VerifierTest extends TestCase
{
    /**
     * @test
     * @throws GuzzleException|InvalidReceiptException
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

    /**
     * @test
     * @throws GuzzleException|InvalidReceiptException
     */
    public function test_sandbox_receipt_with_only_one_receipt()
    {
        $iosReceipt = json_decode(file_get_contents(__DIR__ . '/../single-receipt.json'), true);
        $receiptData = $iosReceipt['token'];
        $client = ClientFactory::createSandbox();
        $password = getenv('PASSWORD');

        $receipt = new Verifier($client, $receiptData, $password);
        $response = $receipt->verifyRenewable();

        $this->assertInstanceOf(ReceiptResponse::class, $response);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_it_throws_exception_on_invalid_responses()
    {
        $this->expectException(InvalidReceiptException::class);

        $iosReceipt = json_decode(file_get_contents(__DIR__ . '/../single-receipt.json'), true);
        $receiptData = $iosReceipt['token'];
        $client = ClientFactory::createSandbox();

        (new Verifier($client, $receiptData, ''))->verifyRenewable();
    }
}
