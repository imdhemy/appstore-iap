<?php

namespace Imdhemy\AppStore\ServerNotifications;

/**
 * Class V2DecodedPayload
 * A decoded payload containing the version 2 notification data.
 *
 * @see https://developer.apple.com/documentation/appstoreservernotifications/responsebodyv2decodedpayload
 */
final class V2DecodedPayload
{
    /**
     * @var string
     */
    private string $notificationType;

    /**
     * @var string
     */
    private string $subType;

    /**
     * @var string
     */
    private string $notificationUUID;

    /**
     * @var array
     */
    private array $data;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var int
     */
    private int $signedDate;

    /**
     * Prevent direct instantiation from outside
     */
    private function __construct()
    {
        //
    }
}
