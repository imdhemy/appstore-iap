<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use PHPUnit\Framework\Attributes\Test;
use Imdhemy\AppStore\Jws\AppStoreJwsVerifier;
use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use JsonException;

class AppStoreJwsVerifierTest extends TestCase
{
    /**
     * @throws JsonException
     */
    #[Test]
    public function verify(): void
    {
        $signedPayload = $this->faker->signedPayload();
        $jws = Parser::toJws($signedPayload);

        $sut = new AppStoreJwsVerifier();

        $this->assertTrue($sut->verify($jws));
    }
}
