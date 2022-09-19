<?php

namespace Imdhemy\AppStore\ValueObjects;

use Imdhemy\AppStore\Jws\JsonWebSignature;
use Stringable;

final class JwsTransactionInfo implements Stringable
{
    /**
     * @var JsonWebSignature
     */
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
