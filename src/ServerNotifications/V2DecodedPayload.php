<?php

namespace Imdhemy\AppStore\ServerNotifications;

use Imdhemy\AppStore\Contracts\Arrayable;
use Imdhemy\AppStore\Jws\JsonWebSignature;

/**
 * Class V2DecodedPayload
 * A decoded payload containing the version 2 notification data.
 *
 * @see https://developer.apple.com/documentation/appstoreservernotifications/responsebodyv2decodedpayload
 */
final class V2DecodedPayload implements Arrayable
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
     * @var array
     */
    private array $rawData;

    /**
     * Prevent direct instantiation from outside
     */
    private function __construct(array $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Create a new instance from a Json Web Signature
     *
     * @param JsonWebSignature $jws
     *
     * @return static
     */
    public static function fromJws(JsonWebSignature $jws): self
    {
        return new self($jws->getClaims());
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->rawData;
    }
}
