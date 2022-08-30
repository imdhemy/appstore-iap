<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Imdhemy\AppStore\ValueObjects\ExpirationIntent;
use PHPUnit\Framework\TestCase;

/**
 * It's obvious that there is no need to test getter method
 * but this test was added to maintain the convention of cover every class
 * with a unit test.
 */
class ExpirationIntentTest extends TestCase
{
    private const REASONS = [
        ExpirationIntent::VOLUNTARY_CANCEL,
        ExpirationIntent::BILLING_ERROR,
        ExpirationIntent::DID_NOT_AGREE_PRICE_INCREASE,
        ExpirationIntent::PRODUCT_UNAVAILABLE,
        ExpirationIntent::UNKNOWN_ERROR,
    ];

    public function test_basic_usage(): void
    {
        $reason = self::REASONS[array_rand(self::REASONS)];

        $expirationIntent = new ExpirationIntent($reason);

        $this->assertEquals($reason, $expirationIntent->getValue());
    }

    /**
     * @test
     */
    public function it_should_be_stringable(): void
    {
        $reason = self::REASONS[array_rand(self::REASONS)];

        $expirationIntent = new ExpirationIntent($reason);

        $this->assertEquals((string)$reason, (string)$expirationIntent);
    }
}
