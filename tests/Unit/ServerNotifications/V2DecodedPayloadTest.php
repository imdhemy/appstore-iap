<?php

namespace Imdhemy\AppStore\Tests\Unit\ServerNotifications;

use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\ServerNotifications\V2DecodedPayload;
use Imdhemy\AppStore\Tests\TestCase;

class V2DecodedPayloadTest extends TestCase
{
    /**
     * @test
     */
    public function v2_decoded_payload(): void
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
    }
}
