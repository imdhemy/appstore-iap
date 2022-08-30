<?php

namespace Imdhemy\AppStore\Contracts;

/**
 * Interface Arrayable
 */
interface Arrayable
{
    /**
     * Convert the object to its array representation.
     *
     * @return array
     */
    public function toArray(): array;
}
