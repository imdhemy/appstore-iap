<?php

namespace Imdhemy\AppStore\Tests\Unit\Jws;

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

        // Parse the signed payload
        $jws = Parser::toJws($signedPayload);

        // Extract the X.509 certificate chain
        $certificateChain = $jws->getHeaders()['x5c'];

        $certPath = __DIR__ . '/../../../AppleRootCA-G3.cer';
        $r = $this->verifyCertificateChain($certificateChain, $certPath);

        $this->assertTrue($r);
    }


    private function verifyCertificateChain(array $chain, string $cer): bool
    {
        $rootPem = $this->convertCerToPem($cer);
        $rootPublicKey = openssl_get_publickey($rootPem);

        if (openssl_x509_verify($this->qualifyChainCert($chain[0]), $this->qualifyChainCert($chain[1])) !== 1) {
            return false;
        }

        if (openssl_x509_verify($this->qualifyChainCert($chain[1]), $this->qualifyChainCert($chain[2])) !== 1) {
            return false;
        }

        if (openssl_x509_verify($this->qualifyChainCert($chain[2]), $rootPublicKey) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * @param string $cert
     *
     * @return string
     */
    public function qualifyChainCert(string $cert): string
    {
        return '-----BEGIN CERTIFICATE-----' . PHP_EOL . $cert . PHP_EOL . '-----END CERTIFICATE-----';
    }

    private function convertCerToPem(string $path): string
    {
        $cerContents = file_get_contents($path);
        $pem = chunk_split(base64_encode($cerContents), 64, PHP_EOL);

        return "-----BEGIN CERTIFICATE-----" . PHP_EOL . $pem . "-----END CERTIFICATE-----" . PHP_EOL;
    }
}
