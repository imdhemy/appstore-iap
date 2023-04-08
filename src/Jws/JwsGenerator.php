<?php

namespace Imdhemy\AppStore\Jws;

/**
 * JWS generator interface
 */
interface JwsGenerator
{
    /**
     * Generate a JWT
     *
     * @param array $claims
     * @param array $headers
     *
     * @return Jws
     */
    public function generate(array $claims = [], array $headers = []): JsonWebSignature;
}
