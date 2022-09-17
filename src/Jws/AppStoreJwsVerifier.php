<?php

namespace Imdhemy\AppStore\Jws;

/**
 * App Store JWS Verifier
 * Verifies a signed payload is signed by Apple
 */
class AppStoreJwsVerifier implements JwsVerifier
{
    public const APPLE_ROOT_CA_G3 = __DIR__ . '/../../AppleRootCA-G3.cer';

    /**
     * Verifies the JWS
     *
     * @param JsonWebSignature $jws
     *
     * @return bool
     */
    public function verify(JsonWebSignature $jws): bool
    {
        $chain = $jws->getHeaders()['x5c'];
        $applePublicKey = $this->applePublicKey();

        if (count($chain) !== 3) {
            return false;
        }

        // Verify root certificate against apple public key
        if (openssl_x509_verify($this->qualifyChainCert($chain[2]), $applePublicKey) !== 1) {
            return false;
        }

        // Verify intermediate certificate against root certificate
        if (openssl_x509_verify($this->qualifyChainCert($chain[1]), $this->qualifyChainCert($chain[2])) !== 1) {
            return false;
        }

        // Verify leaf certificate against intermediate certificate
        if (openssl_x509_verify($this->qualifyChainCert($chain[0]), $this->qualifyChainCert($chain[1])) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * Get the Apple public key
     *
     * @return false|resource
     */
    private function applePublicKey()
    {
        $cer = file_get_contents(self::APPLE_ROOT_CA_G3);
        $key = chunk_split(base64_encode($cer), 64, PHP_EOL);
        $pem = "-----BEGIN CERTIFICATE-----" . PHP_EOL . $key . "-----END CERTIFICATE-----" . PHP_EOL;

        return openssl_get_publickey($pem);
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
}
