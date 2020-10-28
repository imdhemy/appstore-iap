<?php

namespace Imdhemy\AppStore;

use Imdhemy\AppStore\Tests\TestCase;

class ClientFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function test_create()
    {
        $client = ClientFactory::createSandbox();
        $baseUri = (string)$client->getConfig()['base_uri'];
        $this->assertEquals(ClientFactory::BASE_URI_SANDBOX, $baseUri);
    }
}
