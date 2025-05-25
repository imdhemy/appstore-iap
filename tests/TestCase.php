<?php

namespace Imdhemy\AppStore\Tests;

use JsonException;
use ReflectionClass;

/**
 * Class TestCase
 * All test cases should extend this class
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Faker $faker;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    protected function getSubscriptionReceipt(): string
    {
        return file_get_contents(__DIR__.'/fixtures/subscription_receipt.json');
    }


    /**
     *
     * @throws JsonException
     */
    protected function getVerifyReceiptResponse(array $override = []): string
    {
        $contents = file_get_contents(__DIR__.'/fixtures/verify_receipt_response.json');
        $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        $response = array_merge($data, $override);

        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    /**
     * Get RSA private key contents
     *
     */
    protected function getRsaPrivateKey(): string
    {
        return file_get_contents(__DIR__.'/fixtures/keys/rsa-private.pem');
    }

    /**
     * Get RSA public key contents
     *
     */
    protected function getRsaPublicKey(): string
    {
        return file_get_contents(__DIR__.'/fixtures/keys/rsa-public.pem');
    }

    /**
     * Get EC private key contents
     *
     */
    protected function getEcdsaPrivateKey(): string
    {
        return file_get_contents(__DIR__.'/fixtures/keys/ecdsa-private.pem');
    }

    /**
     * Get EC public key contents
     *
     */
    protected function getEcdsaPublicKey(): string
    {
        return file_get_contents(__DIR__.'/fixtures/keys/ecdsa-public.pem');
    }

    protected function getPropertyByReflection(object $object, string $property): mixed
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($property);

        return $property->getValue($object);
    }
}
