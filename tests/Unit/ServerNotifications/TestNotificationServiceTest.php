<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\Tests\Unit\ServerNotifications;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Imdhemy\AppStore\ClientFactory;
use Imdhemy\AppStore\Jws\AppStoreJwsGenerator;
use Imdhemy\AppStore\Jws\GeneratorConfig;
use Imdhemy\AppStore\Jws\Issuer;
use Imdhemy\AppStore\Jws\Key;
use Imdhemy\AppStore\ServerNotifications\TestNotificationService;
use Imdhemy\AppStore\Tests\TestCase;
use JsonException;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class TestNotificationServiceTest extends TestCase
{
    /**
     * @test
     * @throws GuzzleException
     * @throws JsonException
     */
    public function request(): void
    {
        // Given
        $body = json_encode(['testNotificationToken' => $this->faker->uuid()], JSON_THROW_ON_ERROR);
        $transactions = [];
        $client = ClientFactory::mock(new Response(200, [], $body), $transactions);

        $key = new Key('kid', InMemory::plainText($this->getEcdsaPrivateKey()));
        $signer = new Sha256();
        $issuer = new Issuer('signer_id', 'bundle_id', $key, $signer);
        $config = GeneratorConfig::forAppStore($issuer);

        $appstoreJwsGenerator = new AppStoreJwsGenerator($config);

        $sut = new TestNotificationService($client, $appstoreJwsGenerator);

        // When
        $response = $sut->request();

        // Then
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('testNotificationToken', $content);
        $this->assertCount(1, $transactions);

        /** @var Request $transactionRequest */
        $transactionRequest = $transactions[0]['request'];
        $this->assertEquals('POST', $transactionRequest->getMethod());
        $this->assertEquals('/inApps/v1/notifications/test', $transactionRequest->getUri()->getPath());
    }
}
