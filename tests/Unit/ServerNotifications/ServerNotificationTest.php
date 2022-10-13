<?php

namespace Imdhemy\AppStore\Tests\Unit\ServerNotifications;

use Exception;
use Imdhemy\AppStore\Receipts\ReceiptResponse;
use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use PHPUnit\Framework\TestCase;

class ServerNotificationTest extends TestCase
{
    /**
     * @test
     */
    public function unified_receipt(): void
    {
        $value = ['status' => 0];
        $serverNotification = ServerNotification::fromArray(
            [
                'unified_receipt' => $value,
                'notification_type' => ServerNotification::CANCEL,
            ]
        );

        $this->assertEquals(0, $serverNotification->getUnifiedReceipt()->getStatus()->getValue());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::CONSUMPTION_REQUEST]);
        $this->assertNull($missingData->getUnifiedReceipt());
    }

    /**
     * @test
     * @throws Exception
     */
    public function auto_renew_status_change_date(): void
    {
        $value = "1606293002000";

        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_status_change_date_ms' => $value,
            ]
        );

        $this->assertEquals(
            $value,
            $serverNotification->getAutoRenewStatusChangeDate()->getCarbon()->getTimestampMs()
        );

        $missingData = ServerNotification::fromArray(
            ['notification_type' => ServerNotification::DID_CHANGE_RENEWAL_PREF]
        );

        $this->assertNull($missingData->getAutoRenewStatusChangeDate());
    }

    /**
     * @test
     */
    public function environment(): void
    {
        $value = ReceiptResponse::ENV_SANDBOX;
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'environment' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getEnvironment());

        $missingData = ServerNotification::fromArray(
            ['notification_type' => ServerNotification::DID_CHANGE_RENEWAL_STATUS]
        );
        $this->assertNull($missingData->getEnvironment());
    }

    /**
     * @test
     */
    public function auto_renew_status(): void
    {
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_status' => "true",
            ]
        );
        $this->assertTrue($serverNotification->getAutoRenewStatus());

        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_status' => true,
            ]
        );
        $this->assertTrue($serverNotification->getAutoRenewStatus());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::DID_FAIL_TO_RENEW]);
        $this->assertNull($missingData->getAutoRenewStatus());
    }

    /**
     * @test
     */
    public function bvrs(): void
    {
        $value = "33";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'bvrs' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getBvrs());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::DID_RECOVER]);
        $this->assertNull($missingData->getBvrs());
    }

    /**
     * @test
     */
    public function bid(): void
    {
        $value = "com.some.thing";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'bid' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getBid());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::DID_RENEW]);
        $this->assertNull($missingData->getBid());
    }

    /**
     * @test
     */
    public function password(): void
    {
        $value = "******";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'password' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getPassword());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::INITIAL_BUY]);
        $this->assertNull($missingData->getPassword());
    }

    /**
     * @test
     */
    public function auto_renew_production_id(): void
    {
        $value = "fake_product_id";
        $serverNotification = ServerNotification::fromArray(
            [
                'notification_type' => ServerNotification::CANCEL,
                'auto_renew_product_id' => $value,
            ]
        );
        $this->assertEquals($value, $serverNotification->getAutoRenewProductId());

        $missingData = ServerNotification::fromArray(['notification_type' => ServerNotification::INTERACTIVE_RENEWAL]);
        $this->assertNull($missingData->getAutoRenewProductId());
    }

    /**
     * @test
     */
    public function notification_type(): void
    {
        $value = ServerNotification::PRICE_INCREASE_CONSENT;
        $serverNotification = ServerNotification::fromArray(
            ['notification_type' => $value]
        );
        $this->assertEquals($value, $serverNotification->getNotificationType());
    }

    /**
     * @test
     */
    public function to_array(): void
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

        $this->assertEquals($attributes, $serverNotification->toArray());
    }
}
