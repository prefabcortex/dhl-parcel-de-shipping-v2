<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Auth;

use InvalidArgumentException;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Exception\NoHttpClientException;
use RuntimeException;

use function getenv;

/**
 * Example: authenticate against the "ApiKey" API key scheme.
 *
 * Usage: set API_KEY_API_KEY in the environment, then
 *
 *   $client = getApiKeyClient();
 *
 * @throws RuntimeException
 * @throws InvalidArgumentException
 * @throws NoHttpClientException
 */
function getApiKeyClient(): Client
{
    $apiKey = getenv('API_KEY_API_KEY');

    if (false === $apiKey) {
        throw new RuntimeException('Set API_KEY_API_KEY before running this example.');
    }

    return Client::withApiKey($apiKey, ClientConfig::production());
}
