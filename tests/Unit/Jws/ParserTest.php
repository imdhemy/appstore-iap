<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use JsonException;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser as JwtParser;

class ParserTest extends TestCase
{
    /**
     * @test
     * @throws JsonException
     */
    public function parse(): void
    {
        $jwtParser = new JwtParser(new JoseEncoder());

        $sut = new Parser($jwtParser);
        $signedPayload = $this->faker->signedPayload();

        $jws = $sut->parse($signedPayload);

        $this->assertSame($signedPayload, (string)$jws);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function toJws(): void
    {
        $signedPayload = $this->faker->signedPayload();

        $jws = Parser::toJws($signedPayload);

        $this->assertSame($signedPayload, (string)$jws);
    }
}
