<?php

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\AppMetadata;

class AppMetadataTest extends TestCase
{
    /**
     * @test
     */
    public function app_meta_data(): void
    {
        $props = [
            'appAppleId' => '123456789',
            'bundleId' => 'com.example.app',
            'bundleVersion' => '1.0.0',
            'environment' => 'Production',
        ];
        $sut = AppMetadata::fromArray($props);

        $this->assertEquals($props['appAppleId'], $sut->appAppleId());
        $this->assertEquals($props['bundleId'], $sut->bundleId());
        $this->assertEquals($props['bundleVersion'], $sut->bundleVersion());
        $this->assertEquals($props['environment'], $sut->environment());
        $this->assertEquals($props, $sut->toArray());
    }
}
