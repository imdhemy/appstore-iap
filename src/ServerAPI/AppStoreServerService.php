<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\ServerAPI;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Imdhemy\AppStore\Jws\JwsGenerator;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AppStoreServerService
 * This class is used to request from App Store Server
 */
final class AppStoreServerService
{
    private ClientInterface $client;

    private JwsGenerator $jwsGenerator;

    private string $version = 'v1';

    public function __construct(ClientInterface $client, JwsGenerator $jwsGenerator, string $version = 'v1')
    {
        $this->client = $client;
        $this->jwsGenerator = $jwsGenerator;
        $this->version = $version;
    }

    public function get(string $url, $params = []): ResponseInterface
    {
        return $this->request($url, 'get', $params);
    }

    public function post(string $url, array $data): ResponseInterface
    {
        return $this->request($url, 'post', [], $data);
    }

    /**
     * Sends requests to App Store Server
     *
     * @throws GuzzleException
     */
    public function request(string $url, $type = 'get', $params = [], $data = null): ResponseInterface
    {
        $jws = $this->jwsGenerator->generate();

        $url = str_replace('//', '/', '/inApps/'.$this->version.'/'.$url);

        return $this->client->{$type}($url, [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $jws),
            ],
            'query' => $params,
            'form_params' => $data,
        ]);
    }
}
