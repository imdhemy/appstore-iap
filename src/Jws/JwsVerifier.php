<?php

namespace Imdhemy\AppStore\Jws;

/**
 * JWS Verifier interface
 */
interface JwsVerifier
{
    /**
     * Verifies the JWS
     *
     * @param JsonWebSignature $jws
     *
     * @return bool
     */
    public function verify(JsonWebSignature $jws): bool;
}
