<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Cancellation;
use Imdhemy\AppStore\ValueObjects\ReceiptInfo;

class ReceiptInfoTest extends TestCase
{
    /**
     * @var array
     */
    private $attributes;

    protected function setUp(): void
    {
        parent::setUp();
        $this->attributes = [
            "quantity" => "1",
            "product_id" => "month_premium",
            "transaction_id" => "1000000739326921",
            "original_transaction_id" => "1000000723811158",
            "purchase_date" => "2020-11-08 19:08:14 Etc/GMT",
            "purchase_date_ms" => "1604862494000",
            "purchase_date_pst" => "2020-11-08 11:08:14 America/Los_Angeles",
            "original_purchase_date" => "2020-11-08 19:07:15 Etc/GMT",
            "original_purchase_date_ms" => "1604862435000",
            "original_purchase_date_pst" => "2020-11-08 11:07:15 America/Los_Angeles",
            "expires_date" => "2020-11-08 19:13:14 Etc/GMT",
            "expires_date_ms" => "1604862794000",
            "expires_date_pst" => "2020-11-08 11:13:14 America/Los_Angeles",
            "web_order_line_item_id" => "1000000057184240",
            "is_trial_period" => "false",
            "is_in_intro_offer_period" => "false",
            "subscription_group_identifier" => "20667927",
        ];
    }

    /**
     * @test
     */
    public function test_it_can_be_created_from_array()
    {
        $this->assertInstanceOf(ReceiptInfo::class, ReceiptInfo::fromArray($this->attributes));
    }

    /**
     * @test
     */
    public function test_it_can_get_cancellation_data()
    {
        $attributes = $this->attributes;
        $attributes['cancellation_date_ms'] = '1604862794000';
        $attributes['cancellation_reason'] = '0';

        $receiptInfo = ReceiptInfo::fromArray($attributes);

        $this->assertTrue($receiptInfo->isCancelled());
        $this->assertInstanceOf(Cancellation::class, $receiptInfo->getCancellation());
    }
}
