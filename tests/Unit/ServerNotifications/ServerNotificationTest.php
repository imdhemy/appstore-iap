<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\Unit\ServerNotifications;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTimeImmutable;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use Imdhemy\AppStore\ValueObjects\Time;
use PHPUnit\Framework\TestCase;

final class ServerNotificationTest extends TestCase
{
    #[Test]
    public function it_reads_notification_details_from_an_array(): void
    {
        $attributes = [
            'unified_receipt' => ['status' => 0],
            'environment' => ReceiptResponse::ENV_SANDBOX,
            'bvrs' => '33',
            'bid' => 'com.some.thing',
            'password' => '******',
            'auto_renew_product_id' => 'product',
            'notification_type' => ServerNotification::PRICE_INCREASE_CONSENT,
        ];

        $actual = ServerNotification::fromArray($attributes);

        $this->assertSame(0, $actual->getUnifiedReceipt()->getStatus()->getValue());
        $this->assertEquals($attributes['environment'], $actual->getEnvironment());
        $this->assertEquals($attributes['bvrs'], $actual->getBvrs());
        $this->assertEquals($attributes['bid'], $actual->getBid());
        $this->assertEquals($attributes['password'], $actual->getPassword());
        $this->assertEquals($attributes['auto_renew_product_id'], $actual->getAutoRenewProductId());
        $this->assertEquals($attributes['notification_type'], $actual->getNotificationType());
    }

    #[DataProvider('enabledStatusProvider')]
    #[Test]
    public function it_recognizes_enabled_auto_renewal(string|bool $status): void
    {
        $subject = ServerNotification::fromArray([
            'notification_type' => ServerNotification::CANCEL,
            'auto_renew_status' => $status,
        ]);

        $actual = $subject->getAutoRenewStatus();

        $this->assertTrue($actual);
    }

    #[Test]
    public function it_preserves_the_original_notification_payload(): void
    {
        $attributes = [
            'notification_type' => ServerNotification::CANCEL,
            'auto_renew_status' => "true",
            'auto_renew_product_id' => "fake_product_id",
            'password' => "******",
            'bid' => "com.some.thing",
            'bvrs' => "33",
            'environment' => ReceiptResponse::ENV_SANDBOX,
            'auto_renew_status_change_date_ms' => "1606293002000",
            'unified_receipt' => ['status' => 0],
        ];
        $serverNotification = ServerNotification::fromArray($attributes);

        $actual = $serverNotification->toArray();

        $this->assertEquals($attributes, $actual);
    }

    public static function enabledStatusProvider(): array
    {
        return [
            'auto renewal is enabled as a string' => ['true'],
            'auto renewal is enabled as a boolean' => [true],
        ];
    }

    #[DataProvider('absentDateProvider')]
    #[Test]
    public function it_returns_null_for_absent_notification_details(array $dates): void
    {
        $attributes = ['notification_type' => ServerNotification::CANCEL] + $dates;

        $actual = ServerNotification::fromArray($attributes);

        $this->assertNull($actual->getUnifiedReceipt());
        $this->assertNull($actual->getAutoRenewStatusChangeDate());
        $this->assertNull($actual->getAutoRenewStatusChangedAt());
        $this->assertNull($actual->getEnvironment());
        $this->assertNull($actual->getAutoRenewStatus());
        $this->assertNull($actual->getBvrs());
        $this->assertNull($actual->getBid());
        $this->assertNull($actual->getPassword());
        $this->assertNull($actual->getAutoRenewProductId());
    }

    #[DataProvider('dateProvider')]
    #[Test]
    public function it_reads_notification_dates_without_losing_milliseconds(
        int|string $timestamp,
        DateTimeImmutable $expectedDate,
        Time $expectedTime
    ): void
    {
        $attributes = [
            'notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS,
            'auto_renew_status_change_date_ms' => $timestamp,
        ];

        $actual = ServerNotification::fromArray($attributes);

        $this->assertEquals($expectedTime, $actual->getAutoRenewStatusChangeDate());
        $this->assertEquals($expectedDate, $actual->getAutoRenewStatusChangedAt());
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
                'auto_renew_status_change_date_ms' => null,
            ]],
        ];
    }
}
