<?php


namespace Imdhemy\AppStore\ValueObjects;

final class ExpirationIntent
{
    /**
     * @var int
     */
    private $value;

    /**
     * ExpirationIntent constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
