<?php

namespace Imdhemy\AppStore\Tests;

use Faker\Factory;
use Faker\Generator;

/**
 * Faker Class
 * This class is used to generate fake data for testing purposes
 *
 * @mixin Generator
 */
class Faker
{
    private Generator $generator;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Create a new Faker instance
     *
     * @param string $locale
     *
     * @return self
     */
    public static function create(string $locale = Factory::DEFAULT_LOCALE): self
    {
        return new self(Factory::create($locale));
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->generator->$name(...$arguments);
    }

    /**
     * @return string
     */
    public function signedPayload(): string
    {
        return '';
    }
}
