<?php

namespace Imdhemy\AppStore\Jws;

use Stringable;

/**
 * JSON Web Signature (JWS) interface
 */
interface JsonWebSignature extends Stringable
{
    /**
     * Get list of headers
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get list of claims
     *
     * @return array
     */
    public function getClaims(): array;

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature(): string;
}
