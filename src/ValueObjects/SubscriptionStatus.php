<?php

namespace Imdhemy\AppStore\ValueObjects;

use Stringable;

/*
 * Subscription statuses for querying the App Store Server Api
 *
 * @see https://developer.apple.com/documentation/appstoreserverapi/status
 *
 * transactionId (Required) The identifier of a transaction that belongs to the customer, and which may be an original transaction identifier
 * $status (optional) An optional filter that indicates the status of subscriptions to include in the response. Your query may specify more than one status query parameter.
 *
 * 1 - The auto-renewable subscription is active.
 * 2- The auto-renewable subscription is expired.
 * 3 - The auto-renewable subscription is in a billing retry period.
 * 4 - The auto-renewable subscription is in a Billing Grace Period.
 * 5 - The auto-renewable subscription is revoked. The App Store refunded the transaction or revoked it from Family Sharing.
 */
final class SubscriptionStatus implements Stringable
{
    public const ACTIVE = 1;

    public const EXPIRED = 2;

    public const BILLING_RETRY_PERIOD = 3;

    public const BILLING_GRACE_PERIOD = 4;

    public const REVOKED = 5;

    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
