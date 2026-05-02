<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\ValueObjects;

class ConsumptionRequest
{
    private $accountTenure;

    private $appAccountToken;

    private $consumptionStatus;

    private $customerConsented;

    private $deliveryStatus;

    private $lifetimeDollarsPurchased;

    private $lifetimeDollarsRefunded;

    private $platform;

    private $playTime;

    private $refundPreference;

    private $sampleContentProvided;

    private $userStatus;

    /**
     * The age of the customer's account.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/accounttenure
     */
    public function accountTenure($value)
    {
        $this->accountTenure = $value;

        return $this;
    }

    /**
     * The UUID of the in-app user account that completed the in-app purchase transaction.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/appaccounttoken
     */
    public function appAccountToken($value)
    {
        $this->appAccountToken = $value;

        return $this;
    }

    /**
     * A value that indicates the extent to which the customer consumed the in-app purchase.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/consumptionstatus
     */
    public function consumptionStatus($value)
    {
        $this->consumptionStatus = $value;

        return $this;
    }

    /**
     * A Boolean value of true or false that indicates whether the customer consented to provide consumption data.
     * Note: The App Store server rejects requests that have a customerConsented value other than true by returning an HTTP 400 error with an InvalidCustomerConsentError.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/customerconsented
     */
    public function customerConsented($value)
    {
        $this->customerConsented = $value;

        return $this;
    }

    /**
     * A value that indicates whether the app successfully delivered an in-app purchase that works properly.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/deliverystatus
     */
    public function deliveryStatus($value)
    {
        $this->deliveryStatus = $value;

        return $this;
    }

    /**
     * A value that indicates the total amount, in USD, of in-app purchases the customer has made in your app, across all platforms.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/lifetimedollarspurchased
     */
    public function lifetimeDollarsPurchased($value)
    {
        $this->lifetimeDollarsPurchased = $value;

        return $this;
    }

    /**
     * A value that indicates the total amount, in USD, of refunds the customer has received, in your app, across all platforms.
     * (Required)
     *
     *  https://developer.apple.com/documentation/appstoreserverapi/lifetimedollarsrefunded
     */
    public function lifetimeDollarsRefunded($value)
    {
        $this->lifetimeDollarsRefunded = $value;

        return $this;
    }

    /**
     * A value that indicates the platform on which the customer consumed the in-app purchase.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/platform
     */
    public function platform($value)
    {
        $this->platform = $value;

        return $this;
    }

    /**
     * A value that indicates the amount of time that the customer used the app.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/playtime
     */
    public function playTime($value)
    {
        $this->playTime = $value;

        return $this;
    }

    /**
     * A value that indicates your preference, based on your operational logic, as to whether Apple should grant the refund.
     * (Optional)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/refundpreference
     */
    public function refundPreference($value)
    {
        $this->refundPreference = $value;

        return $this;
    }

    /**
     * A Boolean value of true or false that indicates whether you provided, prior to its purchase, a free sample or trial of the content, or information about its functionality.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/samplecontentprovided
     */
    public function sampleContentProvided($value)
    {
        $this->sampleContentProvided = $value;

        return $this;
    }

    /**
     * The status of the customer's account.
     * (Required)
     *
     * https://developer.apple.com/documentation/appstoreserverapi/userstatus
     */
    public function userStatus($value)
    {
        $this->userStatus = $value;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'accountTenure' => $this->accountTenure,
            'appAccountToken' => $this->appAccountToken,
            'consumptionStatus' => $this->consumptionStatus,
            'customerConsented' => $this->customerConsented,
            'deliveryStatus' => $this->deliveryStatus,
            'lifetimeDollarsPurchased' => $this->lifetimeDollarsPurchased,
            'lifetimeDollarsRefunded' => $this->lifetimeDollarsRefunded,
            'platform' => $this->platform,
            'playTime' => $this->playTime,
            'refundPreference' => $this->refundPreference ?? null,
            'sampleContentProvided' => $this->sampleContentProvided,
            'userStatus' => $this->userStatus,
        ];
    }
}
