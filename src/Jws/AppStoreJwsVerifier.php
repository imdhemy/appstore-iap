<?php

namespace Imdhemy\AppStore\Jws;

use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

/**
 * App Store JWS Verifier
 * Verifies a signed payload is signed by Apple
 */
class AppStoreJwsVerifier implements JwsVerifier
{
    private const APPLE_CERTIFICATE_FINGERPRINTS = [
        // Fingerprint of https://www.apple.com/certificateauthority/AppleWWDRCAG6.cer
        '0be38bfe21fd434d8cc51cbe0e2bc7758ddbf97b',
        // Fingerprint of https://www.apple.com/certificateauthority/AppleRootCA-G3.cer
        'b52cb02fd567e0359fe8fa4d4c41037970fe01b0',

    ];

    private const CHAIN_LENGTH = 3;

    /**
     * Verifies the JWS
     *
     * @param JsonWebSignature $jws
     *
     * @return bool
     */
    public function verify(JsonWebSignature $jws): bool
    {
        $x5c = $jws->getHeaders()['x5c'] ?? [];

        if (count($x5c) !== self::CHAIN_LENGTH) {
            return false;
        }

        $chain = $this->chain($jws->getHeaders()['x5c'] ?? []);

        $fingerPrints = [];

        for ($i = 0; $i < self::CHAIN_LENGTH; ++$i) {
            $current = $chain[$i];
            $next = $chain[$i + 1] ?? null;

            if ($i !== 0) {
                $fingerPrints[] = openssl_x509_fingerprint($current);
            }

            if ($next !== null && openssl_x509_verify($current, $next) !== 1) {
                return false;
            }
        }

        if (self::APPLE_CERTIFICATE_FINGERPRINTS !== $fingerPrints) {
            return false;
        }

        openssl_x509_export($chain[0], $exportedCertificate);
        (new SignedWith(Sha256::create(), InMemory::plainText($exportedCertificate)))->assert($jws);

        return true;
    }

    /**
     * @param array $certificates
     *
     * @return string[]
     */
    private function chain(array $certificates): array
    {
        $chain = [];

        foreach ($certificates as $certificate) {
            $chain[] = $this->bas464DerToCert($certificate);
        }

        return $chain;
    }

    /**
     * @param string $certificate
     *
     * @return resource
     */
    private function bas464DerToCert(string $certificate)
    {
        $contents =
            '-----BEGIN CERTIFICATE-----' . PHP_EOL .
            $certificate . PHP_EOL .
            '-----END CERTIFICATE-----';

        return openssl_x509_read($contents);
    }
}
