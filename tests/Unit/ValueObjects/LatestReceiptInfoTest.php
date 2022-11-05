<?php

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\LatestReceiptInfo;
use Imdhemy\AppStore\ValueObjects\Time;

class LatestReceiptInfoTest extends TestCase
{
    /**
     * @var string[]
     */
    private array $commonAttributes;

    /**
     * @return array[<string, array>]
     */
    public function trueOrNullDataProvider(): array
    {
        return [
            'true' => ['true', true],
            'null' => [null, null],
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

    /**
     * @test
     */
    public function required_attributes(): void
    {
        $quantity = "1";
        $productId = "fake_product_id";
        $transactionId = "fake_transaction_id";
        $originalTransactionId = "fake_original_transaction_id";

        $latestReceiptInfo = LatestReceiptInfo::fromArray($this->commonAttributes);

        $this->assertEquals($quantity, $latestReceiptInfo->getQuantity());
        $this->assertSame($productId, $latestReceiptInfo->getProductId());
        $this->assertSame($transactionId, $latestReceiptInfo->getTransactionId());
        $this->assertSame($originalTransactionId, $latestReceiptInfo->getOriginalTransactionId());
    }

    /**
     * @test
     */
    public function app_account_token(): void
    {
        $value = $this->faker->uuid();
        $attributes = array_merge($this->commonAttributes, ['app_account_token' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceiptInfo->getAppAccountToken());
    }

    /**
     * @test
     */
    public function cancellation_date_ms(): void
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['cancellation_date_ms' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceiptInfo->getCancellationDate());
    }

    /**
     * @test
     */
    public function cancellation_reason(): void
    {
        $reasons = [LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE, LatestReceiptInfo::CANCELLATION_REASON_OTHER];
        $value = $this->faker->randomElement($reasons);
        $attributes = array_merge($this->commonAttributes, ['cancellation_reason' => (string)$value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceiptInfo->getCancellationReason());
    }

    /**
     * @test
     */
    public function expires_date_ms(): void
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['expires_date_ms' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceipt->getExpiresDate());
    }

    /**
     * @test
     */
    public function in_app_ownership_type(): void
    {
        $types = [
            LatestReceiptInfo::OWNERSHIP_TYPE_FAMILY_SHARED,
            LatestReceiptInfo::OWNERSHIP_TYPE_PURCHASED,
        ];
        $value = $this->faker->randomElement($types);
        $attributes = array_merge($this->commonAttributes, ['in_app_ownership_type' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertSame($value, $latestReceiptInfo->getInAppOwnershipType());
    }

    /**
     * @test
     * @dataProvider trueOrNullDataProvider
     */
    public function is_in_intro_offer_period(?string $value = null, ?bool $expected = null): void
    {
        $attributes = array_merge($this->commonAttributes, ['is_in_intro_offer_period' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $this->assertEquals($expected, $latestReceiptInfo->getIsInIntroOfferPeriod());
    }

    /**
     * @test
     * @dataProvider trueOrNullDataProvider
     */
    public function is_trial_period(?string $value = null, ?bool $expected = null): void
    {
        $attributes = array_merge($this->commonAttributes, ['is_trial_period' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($expected, $latestReceiptInfo->getIsTrialPeriod());
    }

    /**
     * @test
     * @dataProvider trueOrNullDataProvider
     */
    public function is_upgraded(?string $value = null, ?bool $expected = null): void
    {
        $attributes = array_merge($this->commonAttributes, ['is_upgraded' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($expected, $latestReceiptInfo->getIsUpgraded());
    }

    /**
     * @test
     */
    public function offer_code_ref_name(): void
    {
        $value = 'fake_offer_code_ref_name';
        $attributes = array_merge($this->commonAttributes, ['offer_code_ref_name' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceipt->getOfferCodeRefName());
    }

    /**
     * @test
     */
    public function original_purchase_date_ms(): void
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['original_purchase_date_ms' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceipt->getOriginalPurchaseDate());
    }

    /**
     * @test
     */
    public function promotional_offer_id(): void
    {
        $value = 'fake_promotional_offer_id';
        $attributes = array_merge($this->commonAttributes, ['promotional_offer_id' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceipt->getPromotionalOfferId());
    }

    /**
     * @test
     */
    public function purchase_date_ms(): void
    {
        $value = $this->faker->unixTime() * 1000;
        $attributes = array_merge($this->commonAttributes, ['purchase_date_ms' => $value]);
        $latestReceipt = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals(new Time($value), $latestReceipt->getPurchaseDate());
    }

    /**
     * @test
     */
    public function missing_data_is_null(): void
    {
        $notNullGetters = [
            'getQuantity',
            'getProductId',
            'getTransactionId',
            'getOriginalTransactionId',
            'getIsTrialPeriod',
            'getIsUpgraded',
        ];

        $latestReceiptInfo = LatestReceiptInfo::fromArray($this->commonAttributes);
        $getters = array_filter(
            get_class_methods($latestReceiptInfo),
            static function (string $method) use ($notNullGetters) {
                $isGetter = strpos($method, 'get') !== false;
                $isNullGetter = ! in_array($method, $notNullGetters);

                return $isGetter && $isNullGetter;
            }
        );

        foreach ($getters as $getter) {
            $this->assertNull($latestReceiptInfo->$getter());
        }
    }

    /**
     * @deprecated
     * @test
     */
    public function get_cancellation(): void
    {
        $now = time() * 1000;

        $attributes = array_merge($this->commonAttributes, [
            'cancellation_date_ms' => $now,
            'cancellation_reason' => LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE,
        ]);

        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $cancellation = $latestReceiptInfo->getCancellation();

        $cancellationTime = $cancellation->getTime()->toDateTime();
        $this->assertEquals((new Time($now))->toDateTime(), $cancellationTime);
        $this->assertEquals(LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE, $cancellation->getReason());
    }

    /**
     * @test
     */
    public function get_web_order_line_item_id(): void
    {
        $value = 'fake_web_order_line_item_id';
        $attributes = array_merge($this->commonAttributes, ['web_order_line_item_id' => $value]);
        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);
        $this->assertEquals($value, $latestReceiptInfo->getWebOrderLineItemId());
    }

    /**
     * @test
     */
    public function to_array(): void
    {
        $attributes = array_merge($this->commonAttributes, [
            'cancellation_date_ms' => $this->faker->unixTime() * 1000,
            'cancellation_reason' => LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE,
            'web_order_line_item_id' => 'fake_web_order_line_item_id',
        ]);

        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $this->assertEquals($attributes, $latestReceiptInfo->toArray());
    }

    /**
     * @test
     */
    public function json_serialize(): void
    {
        $attributes = array_merge($this->commonAttributes, [
            'cancellation_date_ms' => $this->faker->unixTime() * 1000,
            'cancellation_reason' => LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE,
            'web_order_line_item_id' => 'fake_web_order_line_item_id',
        ]);

        $latestReceiptInfo = LatestReceiptInfo::fromArray($attributes);

        $this->assertEquals($attributes, $latestReceiptInfo->jsonSerialize());
    }
}
