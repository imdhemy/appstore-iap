<?php

namespace Imdhemy\AppStore\Tests;

use JsonException;

/**
 * Class TestCase
 * All test cases should extend this class
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Faker
     */
    protected Faker $faker;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    /**
     * @return string
     */
    protected function getSubscriptionReceipt(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/subscription_receipt.json');
    }


    /**
     * @param array $override
     *
     * @return string
     * @throws JsonException
     */
    protected function getVerifyReceiptResponse(array $override = []): string
    {
        $contents = file_get_contents(__DIR__ . '/fixtures/verify_receipt_response.json');
        $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        $response = array_merge($data, $override);

        return json_encode($response, JSON_THROW_ON_ERROR);
    }

    /**
     * Get RSA private key contents
     *
     * @return string
     */
    protected function getRsaPrivateKey(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/keys/rsa-private.pem');
    }

    /**
     * Get RSA public key contents
     *
     * @return string
     */
    protected function getRsaPublicKey(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/keys/rsa-public.pem');
    }

    /**
     * Get EC private key contents
     *
     * @return string
     */
    protected function getEcdsaPrivateKey(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/keys/ecdsa-private.pem');
    }

    /**
     * Get EC public key contents
     *
     * @return string
     */
    protected function getEcdsaPublicKey(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/keys/ecdsa-public.pem');
    }
}
