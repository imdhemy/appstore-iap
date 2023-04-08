<?php

namespace Imdhemy\AppStore\Tests\Unit\ServerNotifications;

use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Time;

class V2DecodedPayloadTest extends TestCase
{
    /**
     * @test
     */
    public function from_jws(): V2DecodedPayload
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

        $sut = V2DecodedPayload::fromJws($jws);

        foreach ($claims as $key => $value) {
            $this->assertEquals($value, $sut->toArray()[$key]);
        }

        return $sut;
    }

    /**
     * @test
     */
    public function from_array(): void
    {
        $claims = [
            'notificationType' => V2DecodedPayload::TYPE_TEST,
            'notificationUUID' => $this->faker->uuid(),
            'data' => [],
            'version' => '2.0',
            'signedDate' => $this->faker->unixTime() * 1000,
        ];

        $sut = V2DecodedPayload::fromArray($claims);

        foreach ($claims as $key => $value) {
            $this->assertEquals($value, $sut->toArray()[$key]);
        }
    }

    /**
     * @depends from_jws
     * @test
     */
    public function get_type(V2DecodedPayload $sut): void
    {
        $this->assertEquals(V2DecodedPayload::TYPE_TEST, $sut->getType());
    }

    /**
     * @depends from_jws
     * @test
     */
    public function subtype_is_null_if_type_is_test(V2DecodedPayload $sut): void
    {
        $this->assertNull($sut->getSubType());
    }

    /**
     * @test
     */
    public function get_notification_uuid(): void
    {
        $claims = ['notificationUUID' => $this->faker->uuid()];
        $signedPayload = $this->faker->jwsOf($claims);
        $jws = Parser::toJws($signedPayload);

        $sut = V2DecodedPayload::fromJws($jws);

        $this->assertEquals($claims['notificationUUID'], $sut->getNotificationUUID());
    }

    /**
     * @test
     */
    public function get_version(): void
    {
        $claims = ['version' => '2.0'];
        $signedPayload = $this->faker->jwsOf($claims);
        $jws = Parser::toJws($signedPayload);

        $sut = V2DecodedPayload::fromJws($jws);

        $this->assertEquals($claims['version'], $sut->getVersion());
    }

    /**
     * @test
     */
    public function get_signed_date(): void
    {
        $claims = ['signedDate' => $this->faker->unixTime() * 1000];
        $signedPayload = $this->faker->jwsOf($claims);
        $jws = Parser::toJws($signedPayload);

        $sut = V2DecodedPayload::fromJws($jws);

        $this->assertTrue((new Time($claims['signedDate']))->equals($sut->getSignedDate()));
    }

    /**
     * @test
     */
    public function get_app_metadata(): void
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

        $this->assertEquals($appAppleId, $sut->getAppMetadata()->appAppleId());
        $this->assertEquals($bundleId, $sut->getAppMetadata()->bundleId());
        $this->assertEquals($bundleVersion, $sut->getAppMetadata()->bundleVersion());
        $this->assertEquals($environment, $sut->getAppMetadata()->environment());
    }

    /**
     * @test
     */
    public function get_renewal_info(): void
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

        $this->assertEquals($token, $sut->getRenewalInfo());
    }

    /**
     * @test
     */
    public function get_transaction_info(): void
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

        $this->assertEquals($token, $sut->getTransactionInfo());
    }
}
