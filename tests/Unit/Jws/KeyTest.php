<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use PHPUnit\Framework\Attributes\Test;
use Imdhemy\AppStore\Jws\Key;
use Imdhemy\AppStore\Tests\TestCase;
use Lcobucci\JWT\Signer\Key\InMemory;

class KeyTest extends TestCase
{
    #[Test]
    public function key(): void
    {
        $kid = $this->faker->uuid();
        $contents = $this->getRsaPrivateKey();
        $jwtKey = InMemory::plainText($contents);

        $sut = new Key($kid, $jwtKey);

        $this->assertEquals($kid, $sut->kid());
        $this->assertEquals($contents, $sut->contents());
        $this->assertEquals($jwtKey->passphrase(), $sut->passphrase());
    }
}
