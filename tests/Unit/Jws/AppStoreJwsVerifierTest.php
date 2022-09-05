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

        $certPath = __DIR__ . '/../../../AppleRootCA-G3.pem';
        $r = $this->verifyCertificateChain($certificateChain, $certPath);

        $pem = $this->convertCerToPem(file_get_contents(__DIR__ . '/../../../AppleRootCA-G3.cer'));

        dd($pem);
    }


    private function verifyCertificateChain(array $chain, $pem): bool
    {
        $rootCert = file_get_contents($pem);
        $rootPublicKey = openssl_get_publickey($rootCert);

        $intermediateCert = '-----BEGIN CERTIFICATE-----' . PHP_EOL . $chain[1] . PHP_EOL . '-----END CERTIFICATE-----';

        $leafCert = '-----BEGIN CERTIFICATE-----' . PHP_EOL . $chain[0] . PHP_EOL . '-----END CERTIFICATE-----';

        $r1 = openssl_x509_verify($leafCert, $intermediateCert);
        $r2 = openssl_x509_verify($intermediateCert, $rootPublicKey);

        return $r1 === 1 && $r2 === 1;
    }

    private function convertCerToPem($cer): string
    {
        $pem = chunk_split(base64_encode($cer), 64, PHP_EOL);

        return "-----BEGIN CERTIFICATE-----" . PHP_EOL . $pem . "-----END CERTIFICATE-----" . PHP_EOL;
    }
}
