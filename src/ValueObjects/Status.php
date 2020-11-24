<?php


namespace Imdhemy\AppStore\ValueObjects;

final class Status
{
    /**
     * @var int
     */
    private $value;

    /**
     * Status constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function isValid(): bool
    {
        return $this->value === 0;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
