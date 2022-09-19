<?php

namespace Imdhemy\AppStore\ValueObjects;

use Imdhemy\AppStore\Jws\JsonWebSignature;
use Stringable;

final class JwsRenewalInfo implements Stringable
{
    private JsonWebSignature $jws;

    /**
     * @param JsonWebSignature $jws
     */
    public function __construct(JsonWebSignature $jws)
    {
        $this->jws = $jws;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->jws->__toString();
    }
}
