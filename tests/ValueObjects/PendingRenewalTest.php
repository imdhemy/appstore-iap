<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\ValueObjects;

use DateTimeImmutable;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\AutoRenewStatus;
use Imdhemy\AppStore\ValueObjects\ExpirationIntent;
use Imdhemy\AppStore\ValueObjects\PendingRenewal;
use Imdhemy\AppStore\ValueObjects\Time;

final class PendingRenewalTest extends TestCase
{

    /**
     * @var array
     */
    private $commonAttributes;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->commonAttributes = [
            'auto_renew_product_id' => 'auto_renew_product_id',
            'original_transaction_id' => 'original_transaction_id',
            'product_id' => 'product_id',
        ];
    }

    /**
     * @test
     */
    public function it_reads_renewal_details_from_an_array(): void
    {
        $attributes = $this->commonAttributes + [
            'auto_renew_status' => AutoRenewStatus::WILL_RENEW,
            'expiration_intent' => ExpirationIntent::BILLING_ERROR,
            'is_in_billing_retry_period' => PendingRenewal::STILL_ATTEMPTING_TO_RENEW,
            'offer_code_ref_name' => 'offer_code_ref_name',
            'price_consent_status' => PendingRenewal::PRICE_INCREASE_CONSENT,
            'promotional_offer_id' => 'promotional_offer_id',
        ];

        $actual = PendingRenewal::fromArray($attributes);

        $this->assertSame($attributes['auto_renew_product_id'], $actual->getAutoRenewProductId());
        $this->assertSame($attributes['original_transaction_id'], $actual->getOriginalTransactionId());
        $this->assertSame($attributes['product_id'], $actual->getProductId());
        $this->assertEquals($attributes['auto_renew_status'], $actual->getAutoRenewStatus());
        $this->assertEquals($attributes['expiration_intent'], $actual->getExpirationIntent());
        $this->assertEquals($attributes['is_in_billing_retry_period'], $actual->getIsInBillingRetryPeriod());
        $this->assertEquals($attributes['offer_code_ref_name'], $actual->getOfferCodeRefName());
        $this->assertEquals($attributes['price_consent_status'], $actual->getPriceConsentStatus());
        $this->assertEquals($attributes['promotional_offer_id'], $actual->getPromotionalOfferId());
    }

    /**
     * @test
     * @dataProvider absentDateProvider
     */
    public function it_returns_null_for_absent_renewal_details(array $dates): void
    {
        $attributes = $this->commonAttributes + $dates;

        $actual = PendingRenewal::fromArray($attributes);

        $this->assertNull($actual->getAutoRenewStatus());
        $this->assertNull($actual->getExpirationIntent());
        $this->assertNull($actual->getGracePeriodExpiresDate());
        $this->assertNull($actual->getGracePeriodExpiresAt());
        $this->assertNull($actual->getIsInBillingRetryPeriod());
        $this->assertNull($actual->getOfferCodeRefName());
        $this->assertNull($actual->getPriceConsentStatus());
        $this->assertNull($actual->getPromotionalOfferId());
    }

    /**
     * @test
     * @dataProvider dateProvider
     */
    public function it_reads_renewal_dates_without_losing_milliseconds(
        int|string $timestamp,
        DateTimeImmutable $expectedDate,
        Time $expectedTime
    ): void
    {
        $attributes = $this->commonAttributes + ['grace_period_expires_date_ms' => $timestamp];

        $actual = PendingRenewal::fromArray($attributes);

        $this->assertEquals($expectedTime, $actual->getGracePeriodExpiresDate());
        $this->assertEquals($expectedDate, $actual->getGracePeriodExpiresAt());
    }

    public static function dateProvider(): array
    {
        return [
            'integer timestamps retain milliseconds' => [
                1580000000123,
                new DateTimeImmutable('@1580000000.123'),
                new Time(1580000000123),
            ],
            'string timestamps retain milliseconds' => [
                '1580000000123',
                new DateTimeImmutable('@1580000000.123'),
                new Time(1580000000123),
            ],
            'zero timestamps represent the Unix epoch' => [0, new DateTimeImmutable('@0.000'), new Time(0)],
            'string zero timestamps represent the Unix epoch' => ['0', new DateTimeImmutable('@0.000'), new Time(0)],
        ];
    }

    public static function absentDateProvider(): array
    {
        return [
            'date fields are omitted' => [[]],
            'date fields are null' => [[
                'grace_period_expires_date_ms' => null,
            ]],
        ];
    }
}
