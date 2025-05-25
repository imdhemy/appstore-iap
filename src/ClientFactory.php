<?php

declare(strict_types=1);

namespace Imdhemy\AppStore;

use ArrayAccess;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class ClientFactory
{
    /**@deprecated use {@see self::ITUNES_PRODUCTION_URI} */
    public const BASE_URI = 'https://buy.itunes.apple.com';
    /**@deprecated use {@see self::ITUNES_SANDBOX_URI} */
    public const BASE_URI_SANDBOX = 'https://sandbox.itunes.apple.com';

    public const STORE_KIT_PRODUCTION_URI = 'https://api.storekit.itunes.apple.com';
    public const STORE_KIT_SANDBOX_URI = 'https://api.storekit-sandbox.itunes.apple.com';
    public const ITUNES_PRODUCTION_URI = 'https://buy.itunes.apple.com';
    public const ITUNES_SANDBOX_URI = 'https://sandbox.itunes.apple.com';

    /**
     * @deprecated use specific create methods instead.
     */
    public static function create(bool $sandbox = false, array $options = []): ClientInterface
    {
        $options = array_merge(
            ['base_uri' => $sandbox ? self::ITUNES_SANDBOX_URI : self::ITUNES_PRODUCTION_URI],
            $options
        );

        return new Client($options);
    }

    /**
     * @deprecated use specific create methods instead.
     */
    public static function createSandbox(array $options = []): ClientInterface
    {
        $options = array_merge(['base_uri' => self::ITUNES_SANDBOX_URI], $options);

        return new Client($options);
    }

    /**
     * Creates a client that returns the specified response
     *
     * @param array|ArrayAccess<int, array> $container Container to hold the history (by reference).
     */
    public static function mock(ResponseInterface $responseMock, array|ArrayAccess &$container = []): ClientInterface
    {
        $mockHandler = new MockHandler([$responseMock]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::history($container));

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Creates a client that returns the specified array of responses in queue order
     *
     * @param array<int, ResponseInterface|RequestExceptionInterface> $responseQueue
     * @param array|ArrayAccess<int, array>                           $container Container to hold the history (by
     *                                                                           reference).
     */
    public static function mockQueue(array $responseQueue, array|ArrayAccess &$container = []): ClientInterface
    {
        $mockHandler = new MockHandler($responseQueue);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::history($container));

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Creates a client that returns the specified request exception
     *
     * @param array|ArrayAccess<int, array> $container Container to hold the history (by reference).
     */
    public static function mockError(
        RequestExceptionInterface $error,
        array|ArrayAccess &$container = []
    ): ClientInterface {
        $mockHandler = new MockHandler([$error]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::history($container));

        return new Client(['handler' => $handlerStack]);
    }

    public static function createForStoreKit(array $options = []): ClientInterface
    {
        return self::createByURI(self::STORE_KIT_PRODUCTION_URI, $options);
    }

    public static function createForStoreKitSandbox(array $options = []): ClientInterface
    {
        return self::createByURI(self::STORE_KIT_SANDBOX_URI, $options);
    }

    public static function createForITunes(array $options = []): ClientInterface
    {
        return self::createByURI(self::ITUNES_PRODUCTION_URI, $options);
    }

    public static function createForITunesSandbox(array $options = []): ClientInterface
    {
        return self::createByURI(self::ITUNES_SANDBOX_URI, $options);
    }

    private static function createByURI(string $uri, array $options = []): ClientInterface
    {
        $options = array_merge(['base_uri' => $uri], $options);

        return new Client($options);
    }
}
