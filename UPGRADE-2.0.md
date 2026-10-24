# Upgrade From 1.x to 2.0

## Clients

### Client Factories

**Likelihood Of Impact: Medium**

`ClientFactory::create()` and `ClientFactory::createSandbox()` have been removed. Use
`createForITunes()`, `createForITunesSandbox()`, `createForStoreKit()`, or
`createForStoreKitSandbox()`, depending on the API and environment you use.

`ClientFactory::BASE_URI` and `ClientFactory::BASE_URI_SANDBOX` have been removed. Use
`ITUNES_PRODUCTION_URI` and `ITUNES_SANDBOX_URI`.

## Dates

### Time Value Object

**Likelihood Of Impact: Medium**

`Imdhemy\AppStore\ValueObjects\Time` and its date accessors have been removed.
Use the `DateTimeImmutable` accessors below.

| Classes                                                    | Old accessor                       | Replacement                        |
|------------------------------------------------------------|------------------------------------|------------------------------------|
| `LatestReceiptInfo`, `JwsTransactionInfo`                  | `getExpiresDate()`                 | `getExpiresAt()`                   |
| `LatestReceiptInfo`, `Receipt`, `JwsTransactionInfo`       | `getOriginalPurchaseDate()`        | `getOriginallyPurchasedAt()`       |
| `LatestReceiptInfo`, `JwsTransactionInfo`                  | `getPurchaseDate()`                | `getPurchasedAt()`                 |
| `LatestReceiptInfo`                                        | `getCancellationDate()`            | `getCancelledAt()`                 |
| `Receipt`                                                  | `getExpirationDate()`              | `getExpiresAt()`                   |
| `Receipt`                                                  | `getPreOrderDate()`                | `getPreOrderedAt()`                |
| `Receipt`                                                  | `getReceiptCreationDate()`         | `getReceiptCreatedAt()`            |
| `Receipt`                                                  | `getRequestDate()`                 | `getRequestedAt()`                 |
| `PendingRenewal`, `JwsRenewalInfo`                         | `getGracePeriodExpiresDate()`      | `getGracePeriodExpiresAt()`        |
| `JwsTransactionInfo`                                       | `getRevocationDate()`              | `getRevokedAt()`                   |
| `JwsTransactionInfo`, `JwsRenewalInfo`, `V2DecodedPayload` | `getSignedDate()`                  | `getSignedAt()`                    |
| `JwsRenewalInfo`                                           | `getRecentSubscriptionStartDate()` | `getRecentSubscriptionStartedAt()` |
| `ServerNotification`                                       | `getAutoRenewStatusChangeDate()`   | `getAutoRenewStatusChangedAt()`    |

The replacements return `?DateTimeImmutable`, except `V2DecodedPayload::getSignedAt()`,
which returns `DateTimeImmutable`. Milliseconds, nulls, and timezone behavior are
preserved. Zero still means no date in `LatestReceiptInfo` and `Receipt`, and the
Unix epoch elsewhere.

```php
// Before
$expiresAt = $receiptInfo->getExpiresDate()?->toDateTime();

// After
$expiresAt = $receiptInfo->getExpiresAt();
$formatted = $expiresAt?->format('Y-m-d\TH:i:s.vP');
$isFuture = null !== $expiresAt && $expiresAt > new DateTimeImmutable();
```

Use `<`, `>`, and `==` instead of `Time::isPast()`, `isFuture()`, and `equals()`.
For dates you construct yourself, `new Time(1580000000123)` becomes
`new DateTimeImmutable('@1580000000.123')`.

### Cancellation Details

**Likelihood Of Impact: Low**

Replace `getCancellation()->getTime()` and `getCancellation()->getReason()` with
`LatestReceiptInfo::getCancelledAt()` and `LatestReceiptInfo::getCancellationReason()`.
