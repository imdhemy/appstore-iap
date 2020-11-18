<?php


namespace Imdhemy\AppStore\ValueObjects;

/**
 * Class Cancellation
 * @package Imdhemy\AppStore\ValueObjects
 */
final class Cancellation
{
    /**
     * @var Time
     */
    private $time;

    /**
     * @var int
     */
    private $reason;

    /**
     * Cancellation constructor.
     * @param Time $time
     * @param int $reason
     */
    public function __construct(Time $time, int $reason)
    {
        $this->time = $time;
        $this->reason = $reason;
    }

    /**
     * @return Time
     */
    public function getTime(): Time
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getReason(): int
    {
        return $this->reason;
    }

    /**
     * @return bool
     */
    public function isDueAppIssue(): bool
    {
        return $this->reason === 0;
    }

    /**
     * @return bool
     */
    public function isDueAnotherReason(): bool
    {
        return $this->reason === 1;
    }
}
