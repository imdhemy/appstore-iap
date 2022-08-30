<?php

namespace Imdhemy\AppStore\Tests\Receipts;

use Exception;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\AppStore\ValueObjects\PendingRenewal;
use Imdhemy\AppStore\ValueObjects\Receipt;
use PHPUnit\Framework\TestCase;

class ReceiptResponseTest extends TestCase
{
    /**
     * @test
     */
    public function all_attributes_are_optional_except_status(): void
    {
        $this->expectNotToPerformAssertions();

        ReceiptResponse::fromArray(['status' => 0]);
    }

    /**
     * @test
     */
    public function environment_attribute(): void
    {
        $environments = [ReceiptResponse::ENV_PRODUCTION, ReceiptResponse::ENV_SANDBOX];

        $body = [
            'status' => 0,
            'environment' => $environments[array_rand($environments)],
        ];

        $response = ReceiptResponse::fromArray($body);
        $this->assertEquals($body['environment'], $response->getEnvironment());

        $missingEnvResponse = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingEnvResponse->getEnvironment());
    }

    /**
     * @test
     * @throws Exception
     */
    public function is_retryable(): void
    {
        $value = random_int(0, 1);
        $body = ['is-retryable' => $value, 'status' => 0];

        $response = ReceiptResponse::fromArray($body);
        $this->assertEquals((bool)$value, $response->getIsRetryable());

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getIsRetryable());
    }

    /**
     * @test
     */
    public function latest_receipt(): void
    {
        $value = base64_decode("fake_receipt_content");
        $response = ReceiptResponse::fromArray(['latest_receipt' => $value, 'status' => 0]);

        $this->assertEquals($value, $response->getLatestReceipt());

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getLatestReceipt());
    }

    /**
     * @test
     */
    public function latest_receipt_info(): void
    {
        $value = [];
        $response = ReceiptResponse::fromArray(['latest_receipt_info' => $value, 'status' => 0]);

        $this->assertIsArray($response->getLatestReceiptInfo());
        $this->assertEmpty($response->getLatestReceiptInfo());

        $valueWithSingleObject = [
            [
                'original_transaction_id' => 'original_transaction_id',
                'product_id' => 'product_id',
                'quantity' => '1',
                'transaction_id' => 'transaction_id',
            ],
        ];
        $response = ReceiptResponse::fromArray(['latest_receipt_info' => $valueWithSingleObject, 'status' => 0]);
        $this->assertInstanceOf(LatestReceiptInfo::class, $response->getLatestReceiptInfo()[0]);

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getLatestReceiptInfo());
    }

    /**
     * @test
     */
    public function pending_renewal_info(): void
    {
        $value = [];
        $response = ReceiptResponse::fromArray(['pending_renewal_info' => $value, 'status' => 0]);

        $this->assertIsArray($response->getPendingRenewalInfo());
        $this->assertEmpty($response->getPendingRenewalInfo());

        $valueWithSingleObject = [
            [
                'auto_renew_product_id' => 'auto_renew_product_id',
                'original_transaction_id' => 'original_transaction_id',
                'product_id' => 'product_id',
            ],
        ];

        $response = ReceiptResponse::fromArray(['pending_renewal_info' => $valueWithSingleObject, 'status' => 0]);
        $this->assertInstanceOf(PendingRenewal::class, $response->getPendingRenewalInfo()[0]);

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getPendingRenewalInfo());
    }

    /**
     * @test
     */
    public function receipt(): void
    {
        $value = [];
        $response = ReceiptResponse::fromArray(['receipt' => $value, 'status' => 0]);

        $this->assertInstanceOf(Receipt::class, $response->getReceipt());

        $missingData = ReceiptResponse::fromArray(['status' => 0]);
        $this->assertNull($missingData->getReceipt());
    }

    /**
     * @test
     */
    public function status(): void
    {
        $statusList = array_merge([0], range(21000, 21010));
        $value = $statusList[array_rand($statusList)];

        $response = ReceiptResponse::fromArray(['status' => $value,]);
        $this->assertEquals($value, $response->getStatus()->getValue());
    }

    /**
     * @test
     */
    public function to_array_should_return_all_attributes(): void
    {
        $body = [
            'status' => 0,
            'environment' => ReceiptResponse::ENV_PRODUCTION,
            'is-retryable' => 'true',
            'latest_receipt' => 'fake_receipt_content',
        ];

        $response = ReceiptResponse::fromArray($body);

        $this->assertEquals($body, $response->toArray());
    }
}
