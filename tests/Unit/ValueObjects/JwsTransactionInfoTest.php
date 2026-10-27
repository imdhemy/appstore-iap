<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTimeImmutable;
use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\JwsTransactionInfo;
use Imdhemy\AppStore\ValueObjects\Time;

final class JwsTransactionInfoTest extends TestCase
{
    #[Test]
    public function it_reads_transaction_details_from_signed_claims(): void
    {
        $claims = [
            'appAccountToken' => '00000000-0000-4000-8000-000000000001',
            'bundleId' => 'com.example.app',
            'environment' => JwsTransactionInfo::ENVIRONMENT_SANDBOX,
            'inAppOwnershipType' => JwsTransactionInfo::OWNERSHIP_TYPE_FAMILY_SHARED,
            'isUpgraded' => true,
            'offerIdentifier' => 'offer-identifier',
            'offerType' => JwsTransactionInfo::OFFER_TYPE_INTRODUCTORY,
            'originalTransactionId' => 'original-transaction',
            'productId' => 'product',
            'quantity' => 2,
            'revocationReason' => JwsTransactionInfo::REVOCATION_REASON_APP_ISSUE,
            'subscriptionGroupIdentifier' => 'subscription-group',
            'transactionId' => 'transaction',
            'type' => JwsTransactionInfo::TYPE_AUTO_RENEWABLE,
            'webOrderLineItemId' => 'web-order-line-item',
        ];
        $jws = Parser::toJws($this->faker->jwsOf($claims));

        $actual = new JwsTransactionInfo($jws);

        $this->assertEquals($claims['appAccountToken'], $actual->getAppAccountToken());
        $this->assertEquals($claims['bundleId'], $actual->getBundleId());
        $this->assertEquals($claims['environment'], $actual->getEnvironment());
        $this->assertEquals($claims['inAppOwnershipType'], $actual->getInAppOwnershipType());
        $this->assertEquals($claims['isUpgraded'], $actual->getIsUpgraded());
        $this->assertEquals($claims['offerIdentifier'], $actual->getOfferIdentifier());
        $this->assertEquals($claims['offerType'], $actual->getOfferType());
        $this->assertEquals($claims['originalTransactionId'], $actual->getOriginalTransactionId());
        $this->assertEquals($claims['productId'], $actual->getProductId());
        $this->assertEquals($claims['quantity'], $actual->getQuantity());
        $this->assertEquals($claims['revocationReason'], $actual->getRevocationReason());
        $this->assertEquals($claims['subscriptionGroupIdentifier'], $actual->getSubscriptionGroupIdentifier());
        $this->assertEquals($claims['transactionId'], $actual->getTransactionId());
        $this->assertEquals($claims['type'], $actual->getType());
        $this->assertEquals($claims['webOrderLineItemId'], $actual->getWebOrderLineItemId());
    }

    /**
     * @return JwsTransactionInfo
     */
    #[Test]
    public function it_preserves_the_original_transaction_signature_and_claims(): JwsTransactionInfo
    {
        $jws = Parser::toJws($this->faker->jwsOf([]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($jws->__toString(), $sut->__toString());
        $this->assertEquals($jws->getClaims(), $sut->getClaims());
        $this->assertEquals($jws->getHeaders(), $sut->getHeaders());
        $this->assertEquals($jws->getSignature(), $sut->getSignature());

        return $sut;
    }

    #[DataProvider('absentDateProvider')]
    #[Test]
    public function it_returns_null_for_absent_transaction_details(array $dates): void
    {
        $jws = Parser::toJws($this->faker->jwsOf($dates));

        $actual = new JwsTransactionInfo($jws);

        $this->assertNull($actual->getExpiresDate());
        $this->assertNull($actual->getExpiresAt());
        $this->assertNull($actual->getOriginalPurchaseDate());
        $this->assertNull($actual->getOriginallyPurchasedAt());
        $this->assertNull($actual->getPurchaseDate());
        $this->assertNull($actual->getPurchasedAt());
        $this->assertNull($actual->getRevocationDate());
        $this->assertNull($actual->getRevokedAt());
        $this->assertNull($actual->getSignedDate());
        $this->assertNull($actual->getSignedAt());
        $this->assertNull($actual->getAppAccountToken());
        $this->assertNull($actual->getBundleId());
        $this->assertNull($actual->getEnvironment());
        $this->assertNull($actual->getInAppOwnershipType());
        $this->assertNull($actual->getIsUpgraded());
        $this->assertNull($actual->getOfferIdentifier());
        $this->assertNull($actual->getOfferType());
        $this->assertNull($actual->getOriginalTransactionId());
        $this->assertNull($actual->getProductId());
        $this->assertNull($actual->getQuantity());
        $this->assertNull($actual->getRevocationReason());
        $this->assertNull($actual->getSubscriptionGroupIdentifier());
        $this->assertNull($actual->getTransactionId());
        $this->assertNull($actual->getType());
        $this->assertNull($actual->getWebOrderLineItemId());
    }

    #[DataProvider('dateProvider')]
    #[Test]
    public function it_reads_transaction_dates_without_losing_milliseconds(
        array $dates,
        array $expectedDates,
        array $expectedTimes
    ): void
    {
        $jws = Parser::toJws($this->faker->jwsOf($dates));

        $actual = new JwsTransactionInfo($jws);

        $this->assertEquals($expectedTimes[0], $actual->getExpiresDate());
        $this->assertEquals($expectedDates[0], $actual->getExpiresAt());
        $this->assertEquals($expectedTimes[1], $actual->getOriginalPurchaseDate());
        $this->assertEquals($expectedDates[1], $actual->getOriginallyPurchasedAt());
        $this->assertEquals($expectedTimes[2], $actual->getPurchaseDate());
        $this->assertEquals($expectedDates[2], $actual->getPurchasedAt());
        $this->assertEquals($expectedTimes[3], $actual->getRevocationDate());
        $this->assertEquals($expectedDates[3], $actual->getRevokedAt());
        $this->assertEquals($expectedTimes[4], $actual->getSignedDate());
        $this->assertEquals($expectedDates[4], $actual->getSignedAt());
    }

    public static function dateProvider(): array
    {
        return [
            'integer timestamps retain milliseconds' => [
                [
                    'expiresDate' => 1580000000123,
                    'originalPurchaseDate' => 1580000001123,
                    'purchaseDate' => 1580000002123,
                    'revocationDate' => 1580000003123,
                    'signedDate' => 1580000004123,
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
                    'expiresDate' => '1580000000123',
                    'originalPurchaseDate' => '1580000001123',
                    'purchaseDate' => '1580000002123',
                    'revocationDate' => '1580000003123',
                    'signedDate' => '1580000004123',
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
            'zero timestamps represent the Unix epoch' => [
                [
                    'expiresDate' => 0,
                    'originalPurchaseDate' => 0,
                    'purchaseDate' => 0,
                    'revocationDate' => 0,
                    'signedDate' => 0,
                ],
                [
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                ],
                [
                    new Time(0),
                    new Time(0),
                    new Time(0),
                    new Time(0),
                    new Time(0),
                ],
            ],
            'string zero timestamps represent the Unix epoch' => [
                [
                    'expiresDate' => '0',
                    'originalPurchaseDate' => '0',
                    'purchaseDate' => '0',
                    'revocationDate' => '0',
                    'signedDate' => '0',
                ],
                [
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                    new DateTimeImmutable('@0.000'),
                ],
                [
                    new Time(0),
                    new Time(0),
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
                'expiresDate' => null,
                'originalPurchaseDate' => null,
                'purchaseDate' => null,
                'revocationDate' => null,
                'signedDate' => null,
            ]],
        ];
    }
}
