<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\ServerAPI;

/**
 * This class is used to build AppStoreServer service
 */
class AppStoreServer
{
    private AppStoreServerService $service;

    public function __construct(array $config, string $version)
    {
        $this->service = (new AppStoreServerBuilder)->of($config)->version($version)->build();
    }

    public function service(): AppStoreServerService
    {
        return $this->service;
    }

    public function parseResponse($response): mixed
    {
        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
