<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2;

use Nyholm\Psr7\Factory\Psr17Factory;
use Prefabcortex\DhlParcelDeShippingV2\Api\GeneralApi;
use Prefabcortex\DhlParcelDeShippingV2\Api\ManifestsApi;
use Prefabcortex\DhlParcelDeShippingV2\Api\ShipmentsAndLabelsApi;
use Prefabcortex\DhlParcelDeShippingV2\Authentication\Authenticator;
use Prefabcortex\DhlParcelDeShippingV2\Authentication\AuthenticatorRegistry;
use Prefabcortex\DhlParcelDeShippingV2\Exception\NoHttpClientException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Http\ClientTrait;
use Prefabcortex\DhlParcelDeShippingV2\Http\HttpClientResolver;
use SensitiveParameter;

use function array_values;

final class Client
{
    use ClientTrait;

    public function general(): GeneralApi
    {
        return new GeneralApi($this);
    }

    public function shipmentsAndLabels(): ShipmentsAndLabelsApi
    {
        return new ShipmentsAndLabelsApi($this);
    }

    public function manifests(): ManifestsApi
    {
        return new ManifestsApi($this);
    }

    /**
     * @throws UnsupportedValueException
     * @throws NoHttpClientException
     */
    public static function withBasicAuth(
        #[SensitiveParameter]
        string $username,
        #[SensitiveParameter]
        string $password,
        ClientConfig $config
    ): self {
        return self::build($config, new Authentication\BasicAuthAuthentication($username, $password));
    }

    /**
     * @throws UnsupportedValueException
     * @throws NoHttpClientException
     */
    public static function withApiKey(
        #[SensitiveParameter]
        string $apiKey,
        ClientConfig $config
    ): self {
        return self::build($config, new Authentication\ApiKeyAuthentication($apiKey));
    }

    /**
     * @throws UnsupportedValueException
     * @throws NoHttpClientException
     */
    public static function withOAuth(
        #[SensitiveParameter]
        string $token,
        ClientConfig $config
    ): self {
        return self::build($config, new Authentication\OAuth2Authentication($token));
    }

    /**
     * A client that signs nothing.
     *
     * For operations the description leaves without a security requirement, and for
     * callers whose own HTTP client already authenticates — see
     * `ClientConfig::withHttpClient()`. A request whose requirements no registered
     * authenticator satisfies goes out unsigned.
     *
     * @throws UnsupportedValueException
     * @throws NoHttpClientException
     */
    public static function create(ClientConfig $config): self
    {
        return self::build($config);
    }

    /**
     * @throws UnsupportedValueException
     * @throws NoHttpClientException
     */
    private static function build(ClientConfig $config, Authenticator ...$authenticators): self
    {
        $factory = new Psr17Factory();
        $httpClient = $config->httpClient->isDefined() ? $config->httpClient->get() : HttpClientResolver::resolve($factory, $factory);

        return new self($config->baseUrl, $httpClient, $factory, $factory, new AuthenticatorRegistry(array_values($authenticators)));
    }
}
