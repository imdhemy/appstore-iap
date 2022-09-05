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
     * @var string
     */
    private string $signedPayload;

    /**
     * @inheritDoc
     * @throws JsonException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $contents = file_get_contents(__DIR__ . '/../../fixtures/signed-payload.json');
        $this->signedPayload = json_decode($contents, true, 512, JSON_THROW_ON_ERROR)['signedPayload'];
    }

    /**
     * @test
     */
    public function parse(): void
    {
        $jwtParser = new JwtParser(new JoseEncoder());

        $sut = new Parser($jwtParser);

        $jws = $sut->parse($this->signedPayload);

        $this->assertSame($this->signedPayload, (string)$jws);
    }

    /**
     * @test
     */
    public function toJws(): void
    {
        $jws = Parser::toJws($this->signedPayload);

        $this->assertSame($this->signedPayload, (string)$jws);
    }
}
