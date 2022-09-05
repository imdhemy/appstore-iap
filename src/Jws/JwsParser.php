<?php

namespace Imdhemy\AppStore\Jws;

/**
 * JWS Parser interface
 */
interface JwsParser
{
    /**
     * Parse a JWT
     *
     * @param string $jws
     *
     * @return Jws
     */
    public function parse(string $jws): Jws;
}
