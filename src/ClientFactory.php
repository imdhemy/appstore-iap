<?php

namespace Imdhemy\AppStore;

use GuzzleHttp\Client;

/**
 * Class ClientFactory
 */
class ClientFactory
{
    const BASE_URI = 'https://buy.itunes.apple.com';
    const BASE_URI_SANDBOX = 'https://sandbox.itunes.apple.com';

    /**
     * @param bool $sandbox
     * @return Client
     */
    public static function create(bool $sandbox = false): Client
    {
        return new Client([
            'base_uri' => $sandbox ? self::BASE_URI_SANDBOX : self::BASE_URI,
        ]);
    }

    /**
     * @return Client
     */
    public static function createSandbox(): Client
    {
        return self::create(true);
    }
}
