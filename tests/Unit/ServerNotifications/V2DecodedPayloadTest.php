<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\Unit\ServerNotifications;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTimeImmutable;
use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Time;

final class V2DecodedPayloadTest extends TestCase
{
    #[Test]
    public function it_reads_notification_details_from_a_signed_payload(): void
    {
        $claims = [
            'notificationType' => V2DecodedPayload::TYPE_TEST,
            'notificationUUID' => $this->faker->uuid(),
            'data' => [],
            'version' => '2.0',
            'signedDate' => $this->faker->unixTime() * 1000,
        ];

        $signedPayload = $this->faker->jwsOf($claims);
        $jws = Parser::toJws($signedPayload);
        $expectedClaims = $jws->getClaims();

        $sut = V2DecodedPayload::fromJws($jws);

        $this->assertSame($expectedClaims, $sut->toArray());
        $this->assertSame($claims['notificationType'], $sut->getType());
        $this->assertSame($claims['notificationUUID'], $sut->getNotificationUUID());
        $this->assertSame($claims['version'], $sut->getVersion());
        $this->assertNull($sut->getSubType());
    }

    #[Test]
    public function it_reads_notification_details_from_an_array(): void
    {
        $claims = [
            'notificationType' => V2DecodedPayload::TYPE_TEST,
            'notificationUUID' => $this->faker->uuid(),
            'data' => [],
            'version' => '2.0',
            'signedDate' => $this->faker->unixTime() * 1000,
        ];

        $sut = V2DecodedPayload::fromArray($claims);

        $this->assertSame($claims, $sut->toArray());
        $this->assertSame($claims['notificationType'], $sut->getType());
        $this->assertSame($claims['notificationUUID'], $sut->getNotificationUUID());
        $this->assertSame($claims['version'], $sut->getVersion());
        $this->assertNull($sut->getSubType());
    }

    #[Test]
    public function it_reads_the_app_metadata_from_the_notification(): void
    {
        $appAppleId = $this->faker->randomNumber();
        $bundleId = $this->faker->domainName();
        $bundleVersion = $this->faker->semver();
        $environment = $this->faker->randomElement(['Sandbox', 'Production']);
        $claims = [
            'data' => [
                'appAppleId' => $appAppleId,
                'bundleId' => $bundleId,
                'bundleVersion' => $bundleVersion,
                'environment' => $environment,
                'signedRenewalInfo' => $this->faker->jwsOf([]),
                'signedTransactionInfo' => $this->faker->jwsOf([]),
            ],
        ];
        $signedPayload = $this->faker->jwsOf($claims);
        $jws = Parser::toJws($signedPayload);
        $sut = V2DecodedPayload::fromJws($jws);

        $actual = $sut->getAppMetadata();

        $this->assertEquals($appAppleId, $actual->appAppleId());
        $this->assertEquals($bundleId, $actual->bundleId());
        $this->assertEquals($bundleVersion, $actual->bundleVersion());
        $this->assertEquals($environment, $actual->environment());
    }

    #[Test]
    public function it_decodes_the_signed_renewal_information(): void
    {
        $token = $this->faker->jwsOf([]);
        $claims = [
            'data' => [
                'signedRenewalInfo' => $token,
            ],
        ];
        $signedPayload = $this->faker->jwsOf($claims);
        $jws = Parser::toJws($signedPayload);
        $sut = V2DecodedPayload::fromJws($jws);

        $actual = $sut->getRenewalInfo();

        $this->assertEquals($token, $actual);
    }

    #[Test]
    public function it_decodes_the_signed_transaction_information(): void
    {
        $token = $this->faker->jwsOf([]);
        $claims = [
            'data' => [
                'signedTransactionInfo' => $token,
            ],
        ];
        $signedPayload = $this->faker->jwsOf($claims);
        $jws = Parser::toJws($signedPayload);
        $sut = V2DecodedPayload::fromJws($jws);

        $actual = $sut->getTransactionInfo();

        $this->assertEquals($token, $actual);
    }

    #[DataProvider('dateProvider')]
    #[Test]
    public function it_reads_notification_dates_without_losing_milliseconds(
        int|string $timestamp,
        DateTimeImmutable $expectedDate,
        Time $expectedTime
    ): void
    {
        $attributes = ['signedDate' => $timestamp];

        $actual = V2DecodedPayload::fromArray($attributes);

        $this->assertEquals($expectedTime, $actual->getSignedDate());
        $this->assertEquals($expectedDate, $actual->getSignedAt());
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
}
