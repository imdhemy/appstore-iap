<?php

namespace Imdhemy\AppStore\Jwt;

/**
 * Generates ES256 JWT token for App Store Connect API
 */
class AppStoreJwtGenerator implements JwtGenerator
{
    public const AUDIENCE = 'appstoreconnect-v1';

    /**
     * @var GeneratorConfig
     */
    private GeneratorConfig $config;

    /**
     * @param GeneratorConfig $config
     */
    public function __construct(GeneratorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Generate a JWT
     *
     * @param array $claims
     * @param array $headers
     *
     * @return Jwt
     */
    public function generate(array $claims = [], array $headers = []): Jwt
    {
        $generatorConfig = $this->config;

        $builder = $this->config->config()->builder();

        $token = $builder->withHeader('kid', $generatorConfig->issuer()->key()->kid())
            ->issuedBy($generatorConfig->issuer()->id())
            ->issuedAt($generatorConfig->clock()->now())
            ->expiresAt($generatorConfig->clock()->now()->modify('+1 hour'))
            ->permittedFor(self::AUDIENCE)
            ->withClaim('bid', $generatorConfig->issuer()->bundle())
            ->getToken($generatorConfig->issuer()->signer(), $generatorConfig->issuer()->key());

        return Jwt::fromJwtPlain($token);
    }

    /**
     * @return GeneratorConfig
     */
    public function getConfig(): GeneratorConfig
    {
        return $this->config;
    }

    /**
     * @param GeneratorConfig $config
     *
     * @return AppStoreJwtGenerator
     */
    public function setConfig(GeneratorConfig $config): AppStoreJwtGenerator
    {
        $this->config = $config;

        return $this;
    }
}
