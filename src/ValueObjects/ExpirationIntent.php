<?php


namespace Imdhemy\AppStore\ValueObjects;

final class ExpirationIntent
{
    const VOLUNTARY_CANCEL = 1;
    const BILLING_ERROR = 2;
    const DID_NOT_AGREE_PRICE_INCREASE = 3;
    const PRODUCT_UNAVAILABLE = 4;
    const UNKNOWN_ERROR = 5;
    
    private $value;

    /**
     * ExpirationIntent constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
