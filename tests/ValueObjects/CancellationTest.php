<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Exception;
use Imdhemy\AppStore\ValueObjects\Cancellation;
use Imdhemy\AppStore\ValueObjects\Time;
use PHPUnit\Framework\TestCase;

/**
 * @deprecated
 */
class CancellationTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_basic_usage(): void
    {
        $time = new Time(time() * 1000);
        $reason = [Cancellation::REASON_OTHER, Cancellation::REASON_APP_ISSUE][random_int(0, 1)];

        $cancellation = new Cancellation($time, $reason);

        $this->assertSame($time, $cancellation->getTime());
        $this->assertSame($reason, $cancellation->getReason());

        if ($reason === Cancellation::REASON_APP_ISSUE) {
            $this->assertTrue($cancellation->isDueAppIssue());
            $this->assertFalse($cancellation->isDueAnotherReason());
        }

        if ($reason === Cancellation::REASON_OTHER) {
            $this->assertTrue($cancellation->isDueAnotherReason());
            $this->assertFalse($cancellation->isDueAppIssue());
        }
    }

    /**
     * @throws Exception
     */
    public function test_instantiation_using_string_reason(): void
    {
        $time = new Time(time() * 1000);
        $reason = (string)[Cancellation::REASON_OTHER, Cancellation::REASON_APP_ISSUE][random_int(0, 1)];

        $cancellation = new Cancellation($time, $reason);
        $this->assertInstanceOf(Cancellation::class, $cancellation);
    }
}
