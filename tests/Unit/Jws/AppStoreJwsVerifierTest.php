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

    /**
     * @test
     * @throws JsonException
     */
    public function verify_should_validate_certificate_chain(): void
    {
        $signedPayload = $this->faker->signedPayload();
        $jws = Parser::toJws($signedPayload);

        $jwsMock = $this->createMock(JsonWebSignature::class);
        $jwsMock->method('getHeaders')->willReturnOnConsecutiveCalls(
            ['x5c' => [$jws->getHeaders()['x5c'][0], $jws->getHeaders()['x5c'][1], $jws->getHeaders()['x5c'][2]]],
            ['x5c' => [$jws->getHeaders()['x5c'][0], $jws->getHeaders()['x5c'][1], 'invalid_root_cert']],
            ['x5c' => [$jws->getHeaders()['x5c'][0], 'invalid_intermediate_cert', $jws->getHeaders()['x5c'][2]]],
            ['x5c' => ['invalid_leaf_cert', $jws->getHeaders()['x5c'][1], $jws->getHeaders()['x5c'][2]]],
        );

        $sut = new AppStoreJwsVerifier();

        $this->assertTrue($sut->verify($jwsMock));
        $this->assertFalse($sut->verify($jwsMock));
        $this->assertFalse($sut->verify($jwsMock));
        $this->assertFalse($sut->verify($jwsMock));
    }
}
