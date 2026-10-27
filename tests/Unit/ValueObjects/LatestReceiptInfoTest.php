<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTimeImmutable;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\AppStore\ValueObjects\Time;

final class LatestReceiptInfoTest extends TestCase
{

    /**
     * @var string[]
     */
    private array $commonAttributes;

    /**
     * @return array[<string, array>]
     */
    public static function trueOrNullDataProvider(): array
    {
        return [
            'the flag is enabled' => ['true', true],
            'the flag is absent' => [null, null],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->commonAttributes = [
            "quantity" => "1",
            "product_id" => "fake_product_id",
            "transaction_id" => "fake_transaction_id",
            "original_transaction_id" => "fake_original_transaction_id",
        ];
    }

    #[Test]
    public function it_reads_purchase_details_from_an_array(): void
    {
        $attributes = $this->commonAttributes + [
            'app_account_token' => '00000000-0000-4000-8000-000000000001',
            'cancellation_reason' => '1',
            'in_app_ownership_type' => LatestReceiptInfo::OWNERSHIP_TYPE_FAMILY_SHARED,
            'offer_code_ref_name' => 'offer_code_ref_name',
            'promotional_offer_id' => 'promotional_offer_id',
            'web_order_line_item_id' => 'web_order_line_item_id',
        ];

        $actual = LatestReceiptInfo::fromArray($attributes);

        $this->assertSame(1, $actual->getQuantity());
        $this->assertSame($attributes['product_id'], $actual->getProductId());
        $this->assertSame($attributes['transaction_id'], $actual->getTransactionId());
        $this->assertSame($attributes['original_transaction_id'], $actual->getOriginalTransactionId());
        $this->assertEquals($attributes['app_account_token'], $actual->getAppAccountToken());
        $this->assertEquals($attributes['cancellation_reason'], $actual->getCancellationReason());
        $this->assertEquals($attributes['in_app_ownership_type'], $actual->getInAppOwnershipType());
        $this->assertEquals($attributes['offer_code_ref_name'], $actual->getOfferCodeRefName());
        $this->assertEquals($attributes['promotional_offer_id'], $actual->getPromotionalOfferId());
        $this->assertEquals($attributes['web_order_line_item_id'], $actual->getWebOrderLineItemId());
    }

    #[DataProvider('trueOrNullDataProvider')]
    #[Test]
    public function it_identifies_purchases_in_an_introductory_offer_period(
        ?string $value = null,
        ?bool $expected = null
    ): void
    {
        $attributes = array_merge($this->commonAttributes, ['is_in_intro_offer_period' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $actual = $latestReceiptInfo->getIsInIntroOfferPeriod();

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('trueOrNullDataProvider')]
    #[Test]
    public function it_identifies_purchases_in_a_trial_period(?string $value = null, ?bool $expected = null): void
    {
        $attributes = array_merge($this->commonAttributes, ['is_trial_period' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $actual = $latestReceiptInfo->getIsTrialPeriod();

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('trueOrNullDataProvider')]
    #[Test]
    public function it_identifies_upgraded_purchases(?string $value = null, ?bool $expected = null): void
    {
        $attributes = array_merge($this->commonAttributes, ['is_upgraded' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $actual = $latestReceiptInfo->getIsUpgraded();

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function it_returns_the_cancellation_date_and_reason(): void
    {
        $now = 1580000000123;
        $expectedTime = new Time($now);
        $attributes = $this->commonAttributes + [
            'cancellation_date_ms' => $now,
            'cancellation_reason' => LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE,
        ];
        $subject = LatestReceiptInfo::fromArray($attributes);

        $actual = $subject->getCancellation();

        $this->assertEquals($expectedTime, $actual->getTime());
        $this->assertSame(1, $actual->getReason());
    }

    #[Test]
    public function it_preserves_the_original_purchase_payload(): void
    {
        $attributes = array_merge($this->commonAttributes, [
            'cancellation_date_ms' => $this->faker->unixTime() * 1000,
            'cancellation_reason' => LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE,
            'web_order_line_item_id' => 'fake_web_order_line_item_id',
        ]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $actual = $latestReceiptInfo->toArray();

        $this->assertEquals($attributes, $actual);
    }

    #[Test]
    public function it_preserves_the_original_purchase_payload_for_json_serialization(): void
    {
        $attributes = array_merge($this->commonAttributes, [
            'cancellation_date_ms' => $this->faker->unixTime() * 1000,
            'cancellation_reason' => LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE,
            'web_order_line_item_id' => 'fake_web_order_line_item_id',
        ]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $actual = $latestReceiptInfo->jsonSerialize();

        $this->assertEquals($attributes, $actual);
    }

    #[DataProvider('absentDateProvider')]
    #[Test]
    public function it_returns_null_for_absent_purchase_details(array $dates): void
    {
        $attributes = $this->commonAttributes + $dates;

        $actual = LatestReceiptInfo::fromArray($attributes);

        $this->assertNull($actual->getCancellationDate());
        $this->assertNull($actual->getCancelledAt());
        $this->assertNull($actual->getExpiresDate());
        $this->assertNull($actual->getExpiresAt());
        $this->assertNull($actual->getOriginalPurchaseDate());
        $this->assertNull($actual->getOriginallyPurchasedAt());
        $this->assertNull($actual->getPurchaseDate());
        $this->assertNull($actual->getPurchasedAt());
        $this->assertNull($actual->getSubscriptionGroupIdentifier());
        $this->assertNull($actual->getWebOrderLineItemId());
        $this->assertNull($actual->getCancellationReason());
        $this->assertNull($actual->getPromotionalOfferId());
        $this->assertNull($actual->getOfferCodeRefName());
        $this->assertNull($actual->getCancellation());
        $this->assertNull($actual->getAppAccountToken());
        $this->assertNull($actual->getInAppOwnershipType());
        $this->assertNull($actual->getIsInIntroOfferPeriod());
    }

    #[DataProvider('dateProvider')]
    #[Test]
    public function it_reads_purchase_dates_without_losing_milliseconds(
        array $dates,
        array $expectedDates,
        array $expectedTimes
    ): void
    {
        $attributes = $this->commonAttributes + $dates;

        $actual = LatestReceiptInfo::fromArray($attributes);

        $this->assertEquals($expectedTimes[0], $actual->getCancellationDate());
        $this->assertEquals($expectedDates[0], $actual->getCancelledAt());
        $this->assertEquals($expectedTimes[1], $actual->getExpiresDate());
        $this->assertEquals($expectedDates[1], $actual->getExpiresAt());
        $this->assertEquals($expectedTimes[2], $actual->getOriginalPurchaseDate());
        $this->assertEquals($expectedDates[2], $actual->getOriginallyPurchasedAt());
        $this->assertEquals($expectedTimes[3], $actual->getPurchaseDate());
        $this->assertEquals($expectedDates[3], $actual->getPurchasedAt());
    }

    public static function dateProvider(): array
    {
        return [
            'integer timestamps retain milliseconds' => [
                [
                    'cancellation_date_ms' => 1580000000123,
                    'expires_date_ms' => 1580000001123,
                    'original_purchase_date_ms' => 1580000002123,
                    'purchase_date_ms' => 1580000003123,
                ],
                [
                    new DateTimeImmutable('@1580000000.123'),
                    new DateTimeImmutable('@1580000001.123'),
                    new DateTimeImmutable('@1580000002.123'),
                    new DateTimeImmutable('@1580000003.123'),
                ],
                [
                    new Time(1580000000123),
                    new Time(1580000001123),
                    new Time(1580000002123),
                    new Time(1580000003123),
                ],
            ],
            'string timestamps retain milliseconds' => [
                [
                    'cancellation_date_ms' => '1580000000123',
                    'expires_date_ms' => '1580000001123',
                    'original_purchase_date_ms' => '1580000002123',
                    'purchase_date_ms' => '1580000003123',
                ],
                [
                    new DateTimeImmutable('@1580000000.123'),
                    new DateTimeImmutable('@1580000001.123'),
                    new DateTimeImmutable('@1580000002.123'),
                    new DateTimeImmutable('@1580000003.123'),
                ],
                [
                    new Time(1580000000123),
                    new Time(1580000001123),
                    new Time(1580000002123),
                    new Time(1580000003123),
                ],
            ],
        ];
    }

    public static function absentDateProvider(): array
    {
        return [
            'date fields are omitted' => [[]],
            'date fields are null' => [[
                'cancellation_date_ms' => null,
                'expires_date_ms' => null,
                'original_purchase_date_ms' => null,
                'purchase_date_ms' => null,
            ]],
            'zero timestamps mean no date' => [[
                'cancellation_date_ms' => 0,
                'expires_date_ms' => 0,
                'original_purchase_date_ms' => 0,
                'purchase_date_ms' => 0,
            ]],
            'string zero timestamps mean no date' => [[
                'cancellation_date_ms' => '0',
                'expires_date_ms' => '0',
                'original_purchase_date_ms' => '0',
                'purchase_date_ms' => '0',
            ]],
        ];
    }
}
