<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\ValueObjects;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use DateTimeImmutable;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Receipt;
use Imdhemy\AppStore\ValueObjects\Time;

final class ReceiptTest extends TestCase
{
    #[DataProvider('dateProvider')]
    #[Test]
    public function it_reads_receipt_details_from_an_array(
        array $dates,
        array $expectedDates,
        array $expectedTimes
    ): void
    {
        $attributes = [
            'adam_id' => 123456789,
            'app_item_id' => 987654321,
            'application_version' => '13',
            'bundle_id' => 'com.some.thing',
            'download_id' => 123456789,
            'receipt_type' => Receipt::RECEIPT_TYPE_SANDBOX,
            'version_external_identifier' => 1234567,
            'original_application_version' => '1.0',
        ] + $dates;

        $actual = Receipt::fromArray($attributes);

        $this->assertEquals($attributes['adam_id'], $actual->getAdamId());
        $this->assertEquals($attributes['app_item_id'], $actual->getAppItemId());
        $this->assertEquals($attributes['application_version'], $actual->getApplicationVersion());
        $this->assertEquals($attributes['bundle_id'], $actual->getBundleId());
        $this->assertEquals($attributes['download_id'], $actual->getDownloadId());
        $this->assertEquals($attributes['receipt_type'], $actual->getReceiptType());
        $this->assertEquals($attributes['version_external_identifier'], $actual->getVersionExternalIdentifier());
        $this->assertEquals($attributes['original_application_version'], $actual->getOriginalApplicationVersion());
        $this->assertEquals($expectedTimes[0], $actual->getOriginalPurchaseDate());
        $this->assertEquals($expectedDates[0], $actual->getOriginallyPurchasedAt());
        $this->assertEquals($expectedTimes[1], $actual->getReceiptCreationDate());
        $this->assertEquals($expectedDates[1], $actual->getReceiptCreatedAt());
        $this->assertEquals($expectedTimes[2], $actual->getRequestDate());
        $this->assertEquals($expectedDates[2], $actual->getRequestedAt());
        $this->assertEquals($expectedTimes[3], $actual->getExpirationDate());
        $this->assertEquals($expectedDates[3], $actual->getExpiresAt());
        $this->assertEquals($expectedTimes[4], $actual->getPreOrderDate());
        $this->assertEquals($expectedDates[4], $actual->getPreOrderedAt());
    }

    #[Test]
    public function it_maps_in_app_purchases_to_receipt_details(): void
    {
        $receipt = Receipt::fromArray([
            'in_app' => [[
                'original_transaction_id' => 'original_transaction_id',
                'product_id' => 'product_id',
                'quantity' => '1',
                'transaction_id' => 'transaction_id',
            ]],
        ]);

        $actual = $receipt->getInApp();

        $this->assertCount(1, $actual);
        $this->assertSame('original_transaction_id', $actual[0]->getOriginalTransactionId());
        $this->assertSame('product_id', $actual[0]->getProductId());
        $this->assertSame(1, $actual[0]->getQuantity());
        $this->assertSame('transaction_id', $actual[0]->getTransactionId());
    }

    #[Test]
    public function it_returns_an_empty_list_when_there_are_no_in_app_purchases(): void
    {
        $receipt = Receipt::fromArray(['in_app' => []]);

        $actual = $receipt->getInApp();

        $this->assertSame([], $actual);
    }

    #[DataProvider('absentDateProvider')]
    #[Test]
    public function it_returns_null_for_absent_receipt_details(array $dates): void
    {
        $attributes = $dates;

        $actual = Receipt::fromArray($attributes);

        $this->assertNull($actual->getAdamId());
        $this->assertNull($actual->getAppItemId());
        $this->assertNull($actual->getApplicationVersion());
        $this->assertNull($actual->getBundleId());
        $this->assertNull($actual->getDownloadId());
        $this->assertNull($actual->getInApp());
        $this->assertNull($actual->getOriginalPurchaseDate());
        $this->assertNull($actual->getOriginallyPurchasedAt());
        $this->assertNull($actual->getReceiptCreationDate());
        $this->assertNull($actual->getReceiptCreatedAt());
        $this->assertNull($actual->getReceiptType());
        $this->assertNull($actual->getRequestDate());
        $this->assertNull($actual->getRequestedAt());
        $this->assertNull($actual->getVersionExternalIdentifier());
        $this->assertNull($actual->getOriginalApplicationVersion());
        $this->assertNull($actual->getExpirationDate());
        $this->assertNull($actual->getExpiresAt());
        $this->assertNull($actual->getPreOrderDate());
        $this->assertNull($actual->getPreOrderedAt());
    }

    public static function dateProvider(): array
    {
        return [
            'integer timestamps retain milliseconds' => [
                [
                    'original_purchase_date_ms' => 1580000000123,
                    'receipt_creation_date_ms' => 1580000001123,
                    'request_date_ms' => 1580000002123,
                    'expiration_date_ms' => 1580000003123,
                    'preorder_date_ms' => 1580000004123,
                ],
                [
                    new DateTimeImmutable('@1580000000.123'),
                    new DateTimeImmutable('@1580000001.123'),
                    new DateTimeImmutable('@1580000002.123'),
                    new DateTimeImmutable('@1580000003.123'),
                    new DateTimeImmutable('@1580000004.123'),
                ],
                [
                    new Time(1580000000123),
                    new Time(1580000001123),
                    new Time(1580000002123),
                    new Time(1580000003123),
                    new Time(1580000004123),
                ],
            ],
            'string timestamps retain milliseconds' => [
                [
                    'original_purchase_date_ms' => '1580000000123',
                    'receipt_creation_date_ms' => '1580000001123',
                    'request_date_ms' => '1580000002123',
                    'expiration_date_ms' => '1580000003123',
                    'preorder_date_ms' => '1580000004123',
                ],
                [
                    new DateTimeImmutable('@1580000000.123'),
                    new DateTimeImmutable('@1580000001.123'),
                    new DateTimeImmutable('@1580000002.123'),
                    new DateTimeImmutable('@1580000003.123'),
                    new DateTimeImmutable('@1580000004.123'),
                ],
                [
                    new Time(1580000000123),
                    new Time(1580000001123),
                    new Time(1580000002123),
                    new Time(1580000003123),
                    new Time(1580000004123),
                ],
            ],
        ];
    }

    public static function absentDateProvider(): array
    {
        return [
            'date fields are omitted' => [[]],
            'date fields are null' => [[
                'original_purchase_date_ms' => null,
                'receipt_creation_date_ms' => null,
                'request_date_ms' => null,
                'expiration_date_ms' => null,
                'preorder_date_ms' => null,
            ]],
            'zero timestamps mean no date' => [[
                'original_purchase_date_ms' => 0,
                'receipt_creation_date_ms' => 0,
                'request_date_ms' => 0,
                'expiration_date_ms' => 0,
                'preorder_date_ms' => 0,
            ]],
            'string zero timestamps mean no date' => [[
                'original_purchase_date_ms' => '0',
                'receipt_creation_date_ms' => '0',
                'request_date_ms' => '0',
                'expiration_date_ms' => '0',
                'preorder_date_ms' => '0',
            ]],
        ];
    }
}
