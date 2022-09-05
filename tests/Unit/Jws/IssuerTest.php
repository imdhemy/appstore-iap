<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

use Imdhemy\AppStore\Jws\Issuer;
use Imdhemy\AppStore\Jws\Key;
use Imdhemy\AppStore\Tests\TestCase;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class IssuerTest extends TestCase
{
    /**
     * @test
     */
    public function issuer(): void
    {
        $id = $this->faker->uuid();
        $bundle = $this->faker->domainName();
        $key = new Key($this->faker->uuid(), InMemory::plainText($this->getRsaPrivateKey()));
        $signer = new Sha256();

        $issuer = new Issuer($id, $bundle, $key, $signer);

        $this->assertEquals($id, $issuer->id());
        $this->assertEquals($bundle, $issuer->bundle());
        $this->assertEquals($key, $issuer->key());
        $this->assertEquals($signer, $issuer->signer());
    }
}
