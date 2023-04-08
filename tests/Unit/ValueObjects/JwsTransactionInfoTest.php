<?php

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\JwsTransactionInfo;
use Imdhemy\AppStore\ValueObjects\Time;

class JwsTransactionInfoTest extends TestCase
{
    /**
     * @test
     * @return JwsTransactionInfo
     */
    public function jws_transaction_info_is_a_jws(): JwsTransactionInfo
    {
        $jws = Parser::toJws($this->faker->jwsOf([]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($jws->__toString(), $sut->__toString());
        $this->assertEquals($jws->getClaims(), $sut->getClaims());
        $this->assertEquals($jws->getHeaders(), $sut->getHeaders());
        $this->assertEquals($jws->getSignature(), $sut->getSignature());

        return $sut;
    }

    /**
     * @test
     * @depends jws_transaction_info_is_a_jws
     */
    public function all_props_are_nullable(JwsTransactionInfo $sut): void
    {
        $props = [
            'appAccountToken',
            'bundleId',
            'environment',
            'expiresDate',
            'inAppOwnershipType',
            'isUpgraded',
            'offerIdentifier',
            'offerType',
            'originalPurchaseDate',
            'originalTransactionId',
            'productId',
            'purchaseDate',
            'quantity',
            'revocationDate',
            'revocationReason',
            'signedDate',
            'subscriptionGroupIdentifier',
            'transactionId',
            'type',
            'webOrderLineItemId',
        ];

        foreach ($props as $prop) {
            $getter = 'get' . ucfirst($prop);
            $this->assertNull($sut->$getter());
        }
    }

    /**
     * @test
     */
    public function app_account_token(): void
    {
        $value = $this->faker->uuid();
        $jws = Parser::toJws($this->faker->jwsOf(['appAccountToken' => $value]));
        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getAppAccountToken());
    }

    /**
     * @test
     */
    public function bundle_id(): void
    {
        $value = $this->faker->domainName();
        $jws = Parser::toJws($this->faker->jwsOf(['bundleId' => $value]));
        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getBundleId());
    }

    /**
     * @test
     */
    public function environment(): void
    {
        $value = $this->faker->randomElement([
            JwsTransactionInfo::ENVIRONMENT_PRODUCTION,
            JwsTransactionInfo::ENVIRONMENT_SANDBOX,
        ]);
        $jws = Parser::toJws($this->faker->jwsOf(['environment' => $value]));
        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getEnvironment());
    }

    /**
     * @test
     */
    public function expires_date(): void
    {
        $expiresDate = $this->faker->unixTime() * 1000;
        $time = new Time($expiresDate);
        $jws = Parser::toJws($this->faker->jwsOf(['expiresDate' => $expiresDate]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertTrue($time->equals($sut->getExpiresDate()));
    }

    /**
     * @test
     */
    public function in_app_ownership_type(): void
    {
        $value = $this->faker->randomElement([
            JwsTransactionInfo::OWNERSHIP_TYPE_FAMILY_SHARED,
            JwsTransactionInfo::OWNERSHIP_TYPE_PURCHASED,
        ]);
        $jws = Parser::toJws($this->faker->jwsOf(['inAppOwnershipType' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getInAppOwnershipType());
    }

    /**
     * @test
     */
    public function is_upgraded(): void
    {
        $value = $this->faker->boolean();
        $jws = Parser::toJws($this->faker->jwsOf(['isUpgraded' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getIsUpgraded());
    }

    /**
     * @test
     */
    public function offer_identifier(): void
    {
        $value = $this->faker->uuid();
        $jws = Parser::toJws($this->faker->jwsOf(['offerIdentifier' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getOfferIdentifier());
    }

    /**
     * @test
     */
    public function offer_type(): void
    {
        $value = $this->faker->randomElement([
            JwsTransactionInfo::OFFER_TYPE_INTRODUCTORY,
            JwsTransactionInfo::OFFER_TYPE_PROMOTIONAL,
            JwsTransactionInfo::OFFER_TYPE_SUBSCRIPTION,
        ]);
        $jws = Parser::toJws($this->faker->jwsOf(['offerType' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getOfferType());
    }

    /**
     * @test
     */
    public function original_purchase_date(): void
    {
        $originalPurchaseDate = $this->faker->unixTime() * 1000;
        $time = new Time($originalPurchaseDate);
        $jws = Parser::toJws($this->faker->jwsOf(['originalPurchaseDate' => $originalPurchaseDate]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertTrue($time->equals($sut->getOriginalPurchaseDate()));
    }

    /**
     * @test
     */
    public function original_transaction_id(): void
    {
        $value = $this->faker->uuid();
        $jws = Parser::toJws($this->faker->jwsOf(['originalTransactionId' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getOriginalTransactionId());
    }

    /**
     * @test
     */
    public function product_id(): void
    {
        $value = $this->faker->uuid();
        $jws = Parser::toJws($this->faker->jwsOf(['productId' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getProductId());
    }

    /**
     * @test
     */
    public function purchase_date(): void
    {
        $purchaseDate = $this->faker->unixTime() * 1000;
        $time = new Time($purchaseDate);
        $jws = Parser::toJws($this->faker->jwsOf(['purchaseDate' => $purchaseDate]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertTrue($time->equals($sut->getPurchaseDate()));
    }

    /**
     * @test
     */
    public function quantity(): void
    {
        $value = $this->faker->randomNumber();
        $jws = Parser::toJws($this->faker->jwsOf(['quantity' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getQuantity());
    }

    /**
     * @test
     */
    public function revocation_date(): void
    {
        $revocationDate = $this->faker->unixTime() * 1000;
        $time = new Time($revocationDate);
        $jws = Parser::toJws($this->faker->jwsOf(['revocationDate' => $revocationDate]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertTrue($time->equals($sut->getRevocationDate()));
    }

    /**
     * @test
     */
    public function revocation_reason(): void
    {
        $value = $this->faker->randomElement([
            JwsTransactionInfo::REVOCATION_REASON_APP_ISSUE,
            JwsTransactionInfo::REVOCATION_REASON_OTHER,
        ]);
        $jws = Parser::toJws($this->faker->jwsOf(['revocationReason' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getRevocationReason());
    }

    /**
     * @test
     */
    public function signed_date(): void
    {
        $signedDate = $this->faker->unixTime() * 1000;
        $time = new Time($signedDate);
        $jws = Parser::toJws($this->faker->jwsOf(['signedDate' => $signedDate]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertTrue($time->equals($sut->getSignedDate()));
    }

    /**
     * @test
     */
    public function subscription_group_identifier(): void
    {
        $value = $this->faker->uuid();
        $jws = Parser::toJws($this->faker->jwsOf(['subscriptionGroupIdentifier' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getSubscriptionGroupIdentifier());
    }

    /**
     * @test
     */
    public function transaction_id(): void
    {
        $value = $this->faker->uuid();
        $jws = Parser::toJws($this->faker->jwsOf(['transactionId' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getTransactionId());
    }

    /**
     * @test
     */
    public function type(): void
    {
        $value = $this->faker->randomElement([
            JwsTransactionInfo::TYPE_AUTO_RENEWABLE,
            JwsTransactionInfo::TYPE_NON_RENEWING_SUBSCRIPTION,
            JwsTransactionInfo::TYPE_NON_CONSUMABLE,
            JwsTransactionInfo::TYPE_CONSUMABLE,
        ]);
        $jws = Parser::toJws($this->faker->jwsOf(['type' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getType());
    }

    /**
     * @test
     */
    public function web_order_line_item_id(): void
    {
        $value = $this->faker->uuid();
        $jws = Parser::toJws($this->faker->jwsOf(['webOrderLineItemId' => $value]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($value, $sut->getWebOrderLineItemId());
    }
}
