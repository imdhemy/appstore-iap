<?php

namespace Imdhemy\AppStore\Tests\Unit\Jwt;

use DateTimeImmutable;
use Imdhemy\AppStore\Jwt\GeneratorConfig;
use Imdhemy\AppStore\Jwt\Issuer;
use Imdhemy\AppStore\Jwt\Key;
use Imdhemy\AppStore\Tests\TestCase;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class GeneratorConfigTest extends TestCase
{
    /**
     * @test
     */
    public function for_app_store(): void
    {
        $key = new Key('kid', InMemory::plainText($this->getEcdsaPrivateKey()));
        $signer = Sha256::create();
        $issuer = new Issuer('signer_id', 'bundle_id', $key, $signer);
        $clock = new FrozenClock(new DateTimeImmutable());

        $sut = GeneratorConfig::forAppStore($issuer, $clock);

        $this->assertSame($issuer, $sut->issuer());
        $this->assertSame($clock, $sut->clock());
        $this->assertSame($signer, $sut->config()->signer());
    }
}
