<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Exception;
use Imdhemy\AppStore\ValueObjects\AutoRenewStatus;
use PHPUnit\Framework\TestCase;

class AutoRenewStatusTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_basic_usage(): void
    {
        $value = [AutoRenewStatus::TURNED_OFF, AutoRenewStatus::WILL_RENEW][random_int(0, 1)];
        $status = new AutoRenewStatus($value);
        $this->assertEquals($value, $status->getValue());
    }

    /**
     * @test
     * @throws Exception
     */
    public function it_should_be_stringable(): void
    {
        $value = [AutoRenewStatus::TURNED_OFF, AutoRenewStatus::WILL_RENEW][random_int(0, 1)];
        $status = new AutoRenewStatus($value);
        $this->assertEquals((string)$value, (string)$status);
    }
}
