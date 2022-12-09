<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\ServerNotifications;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\Jws\JwsGenerator;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TestNotificationService
 * This class is used to request a test notification from App Store
 */
final class TestNotificationService
{
    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

    /**
     * @var JwsGenerator
     */
    private JwsGenerator $jwsGenerator;

    /**
     * @param ClientInterface $client
     * @param JwsGenerator $jwsGenerator
     */
    public function __construct(ClientInterface $client, JwsGenerator $jwsGenerator)
    {
        $this->client = $client;
        $this->jwsGenerator = $jwsGenerator;
    }

    /**
     * Requests a test notification from App Store
     *
     * @throws GuzzleException
     */
    public function request(): ResponseInterface
    {
        $jws = $this->jwsGenerator->generate();

        return $this->client->post('/inApps/v1/notifications/test', [
            'headers' => [
                'Authorization' => sprintf("Bearer %s", $jws),
            ],
        ]);
    }
}
