<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use Imdhemy\AppStore\Jws\AppStoreJwsVerifier;
use Imdhemy\AppStore\Jws\JsonWebSignature;
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

    /**
     * @test
     */
    public function verify_should_fail_if_chain_size_is_not_three(): void
    {
        $jwsMock = $this->createMock(JsonWebSignature::class);
        $jwsMock->method('getHeaders')->willReturnOnConsecutiveCalls(
            ['x5c' => []],
            ['x5c' => [1]],
            ['x5c' => [1, 2]],
            ['x5c' => [1, 2, 3, 4]],
        );

        $sut = new AppStoreJwsVerifier();

        $this->assertFalse($sut->verify($jwsMock));
        $this->assertFalse($sut->verify($jwsMock));
        $this->assertFalse($sut->verify($jwsMock));
        $this->assertFalse($sut->verify($jwsMock));
    }
}
