<?php

namespace Imdhemy\AppStore\Jwt;

/**
 * JWT generator interface
 */
interface JwtGenerator
{
    /**
     * Generate a JWT
     *
     * @param array $claims
     * @param array $headers
     *
     * @return Jwt
     */
    public function generate(array $claims = [], array $headers = []): Jwt;
}
