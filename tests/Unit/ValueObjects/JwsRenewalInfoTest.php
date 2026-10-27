<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTimeImmutable;
use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\JwsRenewalInfo;
use Imdhemy\AppStore\ValueObjects\Time;

final class JwsRenewalInfoTest extends TestCase
{
    #[Test]
    public function it_reads_renewal_details_from_signed_claims(): void
    {
        $claims = [
            'autoRenewProductId' => 'renewal-product',
            'autoRenewStatus' => JwsRenewalInfo::AUTO_RENEW_STATUS_ON,
            'environment' => JwsRenewalInfo::ENVIRONMENT_SANDBOX,
            'expirationIntent' => JwsRenewalInfo::EXPIRATION_INTENT_BILLING_ERROR,
            'isInBillingRetryPeriod' => true,
            'offerIdentifier' => 'offer-identifier',
            'offerType' => JwsRenewalInfo::OFFER_TYPE_INTRODUCTORY,
            'originalTransactionId' => 'original-transaction',
            'priceIncreaseStatus' => JwsRenewalInfo::PRICE_INCREASE_STATUS_CONSENT,
            'productId' => 'product',
        ];
        $jws = Parser::toJws($this->faker->jwsOf($claims));

        $actual = new JwsRenewalInfo($jws);

        $this->assertEquals($claims['autoRenewProductId'], $actual->getAutoRenewProductId());
        $this->assertEquals($claims['autoRenewStatus'], $actual->getAutoRenewStatus());
        $this->assertEquals($claims['environment'], $actual->getEnvironment());
        $this->assertEquals($claims['expirationIntent'], $actual->getExpirationIntent());
        $this->assertEquals($claims['isInBillingRetryPeriod'], $actual->getIsInBillingRetryPeriod());
        $this->assertEquals($claims['offerIdentifier'], $actual->getOfferIdentifier());
        $this->assertEquals($claims['offerType'], $actual->getOfferType());
        $this->assertEquals($claims['originalTransactionId'], $actual->getOriginalTransactionId());
        $this->assertEquals($claims['priceIncreaseStatus'], $actual->getPriceIncreaseStatus());
        $this->assertEquals($claims['productId'], $actual->getProductId());
    }

    /**
     * @return JwsRenewalInfo
     */
    #[Test]
    public function it_preserves_the_original_renewal_signature_and_claims(): JwsRenewalInfo
    {
        $jws = Parser::toJws($this->faker->jwsOf([]));

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($jws->__toString(), $sut->__toString());
        $this->assertEquals($jws->getHeaders(), $sut->getHeaders());
        $this->assertEquals($jws->getClaims(), $sut->getClaims());
        $this->assertEquals($jws->getSignature(), $sut->getSignature());

        return $sut;
    }

    #[DataProvider('absentDateProvider')]
    #[Test]
    public function it_returns_null_for_absent_renewal_details(array $dates): void
    {
        $jws = Parser::toJws($this->faker->jwsOf($dates));

        $actual = new JwsRenewalInfo($jws);

        $this->assertNull($actual->getGracePeriodExpiresDate());
        $this->assertNull($actual->getGracePeriodExpiresAt());
        $this->assertNull($actual->getRecentSubscriptionStartDate());
        $this->assertNull($actual->getRecentSubscriptionStartedAt());
        $this->assertNull($actual->getSignedDate());
        $this->assertNull($actual->getSignedAt());
        $this->assertNull($actual->getAutoRenewProductId());
        $this->assertNull($actual->getAutoRenewStatus());
        $this->assertNull($actual->getEnvironment());
        $this->assertNull($actual->getExpirationIntent());
        $this->assertNull($actual->getIsInBillingRetryPeriod());
        $this->assertNull($actual->getOfferIdentifier());
        $this->assertNull($actual->getOfferType());
        $this->assertNull($actual->getOriginalTransactionId());
        $this->assertNull($actual->getPriceIncreaseStatus());
        $this->assertNull($actual->getProductId());
    }

    #[DataProvider('dateProvider')]
    #[Test]
    public function it_reads_renewal_dates_without_losing_milliseconds(
        array $dates,
        array $expectedDates,
        array $expectedTimes
    ): void
    {
        $jws = Parser::toJws($this->faker->jwsOf($dates));

        $actual = new JwsRenewalInfo($jws);

        $this->assertEquals($expectedTimes[0], $actual->getGracePeriodExpiresDate());
        $this->assertEquals($expectedDates[0], $actual->getGracePeriodExpiresAt());
        $this->assertEquals($expectedTimes[1], $actual->getRecentSubscriptionStartDate());
        $this->assertEquals($expectedDates[1], $actual->getRecentSubscriptionStartedAt());
        $this->assertEquals($expectedTimes[2], $actual->getSignedDate());
        $this->assertEquals($expectedDates[2], $actual->getSignedAt());
    }

    public static function dateProvider(): array
    {
        return [
            'integer timestamps retain milliseconds' => [
                [
                    'gracePeriodExpiresDate' => 1580000000123,
                    'recentSubscriptionStartDate' => 1580000001123,
                    'signedDate' => 1580000002123,
                ],
                [
                    new DateTimeImmutable('@1580000000.123'),
                    new DateTimeImmutable('@1580000001.123'),
                    new DateTimeImmutable('@1580000002.123'),
                ],
                [
                    new Time(1580000000123),
                    new Time(1580000001123),
                    new Time(1580000002123),
                ],
            ],
            'string timestamps retain milliseconds' => [
                [
                    'gracePeriodExpiresDate' => '1580000000123',
                    'recentSubscriptionStartDate' => '1580000001123',
                    'signedDate' => '1580000002123',
                ],
                [
                    new DateTimeImmutable('@1580000000.123'),
                    new DateTimeImmutable('@1580000001.123'),
                    new DateTimeImmutable('@1580000002.123'),
                ],
                [
                    new Time(1580000000123),
                    new Time(1580000001123),
                    new Time(1580000002123),
                ],
            ],
            'zero timestamps represent the Unix epoch' => [
                [
                    'gracePeriodExpiresDate' => 0,
                    'recentSubscriptionStartDate' => 0,
                    'signedDate' => 0,
                ],
                [
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                ],
                [
                    new Time(0),
                    new Time(0),
                    new Time(0),
                ],
            ],
            'string zero timestamps represent the Unix epoch' => [
                [
                    'gracePeriodExpiresDate' => '0',
                    'recentSubscriptionStartDate' => '0',
                    'signedDate' => '0',
                ],
                [
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                ],
                [
                    new Time(0),
                    new Time(0),
                    new Time(0),
                ],
            ],
        ];
    }

    public static function absentDateProvider(): array
    {
        return [
            'date fields are omitted' => [[]],
            'date fields are null' => [[
                'gracePeriodExpiresDate' => null,
                'recentSubscriptionStartDate' => null,
                'signedDate' => null,
            ]],
        ];
    }
}
