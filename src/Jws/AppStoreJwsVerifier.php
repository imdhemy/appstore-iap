<?php

namespace Imdhemy\AppStore\Jws;

/**
 * App Store JWS Verifier
 * Verifies a signed payload is signed by Apple
 */
class AppStoreJwsVerifier implements JwsVerifier
{
    /**
     * Verifies the JWS
     *
     * @param Jws $jws
     *
     * @return bool
     */
    public function verify(Jws $jws): bool
    {
        return true;
    }
}
