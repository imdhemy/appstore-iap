<?php

namespace Imdhemy\AppStore\ValueObjects;

/**
 * Class Cancellation
 * Cancellation class represents two separate parts of information from the
 * response body receipt info, the cancellation time and reason.
 * The time and reason are received in two separate keys, but they are logically
 * related either coupled.
 *
 * @deprecated
 * @see https://developer.apple.com/documentation/appstorereceipts/responsebody/latest_receipt_info
 */
final class Cancellation
{
    /**
     * @deprecated use \Imdhemy\AppStore\ValueObjects\LatestReceiptInfo::CANCELLATION_REASON_APP_ISSUE
     */
    public const REASON_APP_ISSUE = 1;

    /**
     * @deprecated use \Imdhemy\AppStore\ValueObjects\LatestReceiptInfo::CANCELLATION_REASON_OTHER
     */
    public const REASON_OTHER = 0;

    /**
     * The time the App Store refunded a transaction or revoked it from family sharing.
     * @var Time
     */
    private Time $time;

    /**
     * The reason for a refunded or revoked transaction.
     * A value of “1” indicates that the customer canceled their transaction
     * due to an actual or perceived issue within your app.
     *
     * A value of “0” indicates that the transaction was canceled for another reason;
     * for example, if the customer made the purchase accidentally.
     * @var int
     */
    private int $reason;

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
     * @deprecated
     * @return bool
     */
    public function isDueAppIssue(): bool
    {
        return $this->reason === self::REASON_APP_ISSUE;
    }

    /**
     * @deprecated
     * @return bool
     */
    public function isDueAnotherReason(): bool
    {
        return $this->reason === self::REASON_OTHER;
    }
}
