<?php

namespace Imdhemy\AppStore;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\ResponseInterface;

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

    /**
     * Creates a client that returns the specified response
     *
     * @param ResponseInterface $responseMock
     * @return Client
     */
    public static function mock(ResponseInterface $responseMock): Client
    {
        $mockHandler = new MockHandler([$responseMock]);
        $handlerStack = HandlerStack::create($mockHandler);

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Creates a client that returns the specified array of responses in queue order
     *
     * @param array|ResponseInterface[]|RequestExceptionInterface[] $responseQueue
     * @return Client
     */
    public static function mockQueue(array $responseQueue): Client
    {
        $mockHandler = new MockHandler($responseQueue);
        $handlerStack = HandlerStack::create($mockHandler);

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Creates a client that returns the specified request exception
     *
     * @param RequestExceptionInterface $error
     * @return Client
     */
    public static function mockError(RequestExceptionInterface $error): Client
    {
        $mockHandler = new MockHandler([$error]);
        $handlerStack = HandlerStack::create($mockHandler);

        return new Client(['handler' => $handlerStack]);
    }
}
