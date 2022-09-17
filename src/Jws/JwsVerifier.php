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
     * @param Jws $jws
     *
     * @return bool
     */
    public function verify(JsonWebSignature $jws): bool;
}
