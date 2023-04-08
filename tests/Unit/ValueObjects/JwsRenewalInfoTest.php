<?php

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\JwsRenewalInfo;
use Imdhemy\AppStore\ValueObjects\Time;

class JwsRenewalInfoTest extends TestCase
{
    /**
     * @test
     * @return JwsRenewalInfo
     */
    public function jws_renewal_info_is_a_jws(): JwsRenewalInfo
    {
        $jws = Parser::toJws($this->faker->jwsOf([]));
        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($jws->__toString(), $sut->__toString());
        $this->assertEquals($jws->getHeaders(), $sut->getHeaders());
        $this->assertEquals($jws->getClaims(), $sut->getClaims());
        $this->assertEquals($jws->getSignature(), $sut->getSignature());

        return $sut;
    }

    /**
     * @depends jws_renewal_info_is_a_jws
     * @test
     */
    public function all_props_are_nullable(JwsRenewalInfo $sut): void
    {
        $props = [
            'autoRenewProductId',
            'autoRenewStatus',
            'environment',
            'expirationIntent',
            'gracePeriodExpiresDate',
            'isInBillingRetryPeriod',
            'offerIdentifier',
            'offerType',
            'originalTransactionId',
            'priceIncreaseStatus',
            'productId',
            'recentSubscriptionStartDate',
            'signedDate',
        ];

        foreach ($props as $prop) {
            $getter = 'get' . ucfirst($prop);
            $this->assertNull($sut->$getter());
        }
    }

    /**
     * @test
     */
    public function auto_renew_product_id(): void
    {
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'autoRenewProductId' => 'com.example.app.subscription.monthly',
            ])
        );
        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals('com.example.app.subscription.monthly', $sut->getAutoRenewProductId());
    }

    /**
     * @test
     */
    public function auto_renew_status(): void
    {
        $status = $this->faker->randomElement([
            JwsRenewalInfo::AUTO_RENEW_STATUS_OFF,
            JwsRenewalInfo::AUTO_RENEW_STATUS_ON,
        ]);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'autoRenewStatus' => $status,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($status, $sut->getAutoRenewStatus());
    }

    /**
     * @test
     */
    public function environment(): void
    {
        $environment = $this->faker->randomElement([
            JwsRenewalInfo::ENVIRONMENT_PRODUCTION,
            JwsRenewalInfo::ENVIRONMENT_SANDBOX,
        ]);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'environment' => $environment,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($environment, $sut->getEnvironment());
    }

    /**
     * @test
     */
    public function expiration_intent(): void
    {
        $expirationIntent = $this->faker->randomElement([
            JwsRenewalInfo::EXPIRATION_INTENT_CANCEL,
            JwsRenewalInfo::EXPIRATION_INTENT_BILLING_ERROR,
            JwsRenewalInfo::EXPIRATION_INTENT_PRICE_INCREASE_CONSENT,
            JwsRenewalInfo::EXPIRATION_INTENT_PRODUCT_NOT_AVAILABLE,
        ]);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'expirationIntent' => $expirationIntent,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($expirationIntent, $sut->getExpirationIntent());
    }

    /**
     * @test
     */
    public function grace_period_expires_date(): void
    {
        $timestampMs = $this->faker->unixTime() * 1000;
        $time = new Time($timestampMs);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'gracePeriodExpiresDate' => $timestampMs,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertTrue($time->equals($sut->getGracePeriodExpiresDate()));
    }

    /**
     * @test
     */
    public function is_in_billing_retry_period(): void
    {
        $isInBillingRetryPeriod = $this->faker->boolean();
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'isInBillingRetryPeriod' => $isInBillingRetryPeriod,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($isInBillingRetryPeriod, $sut->getIsInBillingRetryPeriod());
    }

    /**
     * @test
     */
    public function offer_identifier(): void
    {
        $offerIdentifier = $this->faker->uuid();
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'offerIdentifier' => $offerIdentifier,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($offerIdentifier, $sut->getOfferIdentifier());
    }

    /**
     * @test
     */
    public function offer_type(): void
    {
        $offerType = $this->faker->randomElement([
            JwsRenewalInfo::OFFER_TYPE_INTRODUCTORY,
            JwsRenewalInfo::OFFER_TYPE_PROMOTIONAL,
            JwsRenewalInfo::OFFER_TYPE_OFFER_CODE,
        ]);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'offerType' => $offerType,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($offerType, $sut->getOfferType());
    }

    /**
     * @test
     */
    public function original_transaction_id(): void
    {
        $originalTransactionId = $this->faker->uuid();
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'originalTransactionId' => $originalTransactionId,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($originalTransactionId, $sut->getOriginalTransactionId());
    }

    /**
     * @test
     */
    public function price_increase_status(): void
    {
        $priceIncreaseStatus = $this->faker->randomElement([
            JwsRenewalInfo::PRICE_INCREASE_STATUS_NOT_RESPONDED,
            JwsRenewalInfo::PRICE_INCREASE_STATUS_CONSENT,
        ]);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'priceIncreaseStatus' => $priceIncreaseStatus,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($priceIncreaseStatus, $sut->getPriceIncreaseStatus());
    }

    /**
     * @test
     */
    public function product_id(): void
    {
        $productId = 'com.example.app.subscription.monthly';
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'productId' => $productId,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertEquals($productId, $sut->getProductId());
    }

    /**
     * @test
     */
    public function recent_subscription_start_date(): void
    {
        $recentSubscriptionStartDate = $this->faker->unixTime() * 1000;
        $time = new Time($recentSubscriptionStartDate);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'recentSubscriptionStartDate' => $recentSubscriptionStartDate,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertTrue($time->equals($sut->getRecentSubscriptionStartDate()));
    }

    /**
     * @test
     */
    public function signed_date(): void
    {
        $signedDate = $this->faker->unixTime() * 1000;
        $time = new Time($signedDate);
        $jws = Parser::toJws(
            $this->faker->jwsOf([
                'signedDate' => $signedDate,
            ])
        );

        $sut = new JwsRenewalInfo($jws);

        $this->assertTrue($time->equals($sut->getSignedDate()));
    }
}
