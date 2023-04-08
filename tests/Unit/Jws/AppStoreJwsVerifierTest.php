<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use Imdhemy\AppStore\Jws\AppStoreJwsVerifier;
use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use JsonException;

class AppStoreJwsVerifierTest extends TestCase
{
    /**
     * @test
     * @throws JsonException
     */
    public function verify(): void
    {
        $signedPayload = $this->faker->signedPayload();
        $jws = Parser::toJws($signedPayload);

        $sut = new AppStoreJwsVerifier();

        $this->assertTrue($sut->verify($jws));
    }
}
