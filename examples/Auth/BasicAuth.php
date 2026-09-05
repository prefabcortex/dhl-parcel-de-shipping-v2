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
 * Example: authenticate against the "BasicAuth" HTTP Basic Auth scheme.
 *
 * Usage: set BASIC_AUTH_USERNAME and BASIC_AUTH_PASSWORD in the environment, then
 *
 *   $client = getBasicAuthClient();
 *
 * @throws RuntimeException
 * @throws InvalidArgumentException
 * @throws NoHttpClientException
 */
function getBasicAuthClient(): Client
{
    $username = getenv('BASIC_AUTH_USERNAME');
    $password = getenv('BASIC_AUTH_PASSWORD');

    if (false === $username || false === $password) {
        throw new RuntimeException('Set BASIC_AUTH_USERNAME and BASIC_AUTH_PASSWORD before running this example.');
    }

    return Client::withBasicAuth($username, $password, ClientConfig::production());
}
