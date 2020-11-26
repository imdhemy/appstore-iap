<?php

namespace Imdhemy\AppStore\Tests\ServerNotifications;

use Imdhemy\AppStore\ServerNotifications\ServerNotification;
use PHPUnit\Framework\TestCase;

class ServerNotificationTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_can_be_constructed_from_array()
    {
        $path = realpath(__DIR__ . '/../../server-notification.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);

        $serverNotification = ServerNotification::fromArray($serverNotificationBody);
        $this->assertInstanceOf(ServerNotification::class, $serverNotification);
    }
}
