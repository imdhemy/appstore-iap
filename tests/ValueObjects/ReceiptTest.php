<?php

namespace Imdhemy\AppStore\Tests\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\Receipt;

class ReceiptTest extends TestCase
{
    /**
     *
     * @test
     */
    public function test_it_handles_missing_original_application_version_attribute()
    {
        $receiptAttributes = [
            'adam_id' => 154123487,
            'app_item_id' => 123142487,
            'application_version' => 12,
            'bundle_id' => 'com.someapp.app',
            'download_id' => null,
            'in_app' => [],
            'original_purchase_date' => '2021-04-02 15:01:28 Etc/GMT',
            'original_purchase_date_ms' => 1617375688000,
            'original_purchase_date_pst' => '2021-04-02 08:01:28 America/Los_Angeles',
            'receipt_creation_date' => '2021-04-02 15:01:32 Etc/GMT',
            'receipt_creation_date_ms' => 1617375688000,
            'receipt_creation_date_pst' => '2021-04-02 08:01:32 America/Los_Angeles',
            'receipt_type' => 'Production',
            'request_date' => '2021-04-02 15:01:33 Etc/GMT',
            'request_date_ms' => 1617375688000,
            'request_date_pst' => '2021-04-02 08:01:33 America/Los_Angeles',
            'version_external_identifier' => 8123418,
            // 'original_application_version' => '1.0',
            // ^ Apple server notifications do not always include this attribute, so crash-test a simulated scenario
        ];

        $actual = Receipt::fromArray($receiptAttributes);

        $this->assertInstanceOf(Receipt::class, $actual);
        $this->assertNull($actual->getOriginalApplicationVersion());
    }
}
