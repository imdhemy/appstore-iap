<?php


namespace Imdhemy\AppStore\ValueObjects;

final class ExpirationIntent
{
    private $value;

    /**
     * ExpirationIntent constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
