<?php

namespace Imdhemy\AppStore\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return false|string
     */
    protected function getSubscriptionReceipt()
    {
        return file_get_contents(__DIR__ . '/../fixtures/subscription_receipt.json');
    }

    /**
     * @return false|string
     */
    protected function getVerifyReceiptResponse()
    {
        return file_get_contents(__DIR__ . '/../fixtures/verify_receipt_response.json');
    }
}
