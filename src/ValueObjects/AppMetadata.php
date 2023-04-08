<?php

namespace Imdhemy\AppStore\ValueObjects;

use Imdhemy\AppStore\Contracts\Arrayable;

final class AppMetadata implements Arrayable
{
    /**
     * @var array
     */
    private array $data;

    /**
     * Prevent direct instantiation
     *
     * @param array $data
     */
    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    /**
     * @return string
     */
    public function appAppleId(): string
    {
        return $this->data['appAppleId'];
    }

    /**
     * @return string
     */
    public function bundleId(): string
    {
        return $this->data['bundleId'];
    }

    /**
     * @return string
     */
    public function bundleVersion(): string
    {
        return $this->data['bundleVersion'];
    }

    /**
     * @return string
     */
    public function environment(): string
    {
        return $this->data['environment'];
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
