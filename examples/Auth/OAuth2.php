<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Auth;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use RuntimeException;

use function array_key_exists;
use function array_slice;
use function explode;
use function getenv;
use function http_build_query;
use function implode;
use function is_array;
use function is_string;
use function json_decode;
use function sprintf;

/**
 * Example: obtain an OAuth2 access token for the "OAuth2" scheme
 * (password grant, see RFC 6749) and use it to authenticate the
 * generated client.
 *
 * Pass the PSR-18 client your application uses. It fetches the token *and* becomes the client
 * the returned Client sends through, so both halves of the exchange go over the same
 * connection settings — timeouts, proxies and TLS options included.
 *
 * Usage: set OAUTH2_USERNAME and OAUTH2_PASSWORD in the environment, then
 *
 *   $client = getOAuth2Client($httpClient);
 *
 * @throws RuntimeException
 * @throws InvalidArgumentException
 * @throws ClientExceptionInterface
 */
function getOAuth2Client(ClientInterface $httpClient): Client
{
    $username = getenv('OAUTH2_USERNAME');
    $password = getenv('OAUTH2_PASSWORD');

    if (false === $username || false === $password) {
        throw new RuntimeException('Set OAUTH2_USERNAME and OAUTH2_PASSWORD before running this example.');
    }

    $config = ClientConfig::production()->withHttpClient($httpClient);

    // A token endpoint written as an absolute path replaces the path of the base URL
    // rather than extending it (RFC 3986 §5.2), so only scheme and authority carry over.
    $origin = implode('/', array_slice(explode('/', $config->baseUrl), 0, 3));
    $tokenUrl = $origin . '/parcel/de/account/auth/ropc/v1/token';

    $formFields = [
        'grant_type' => 'password',
        'username' => $username,
        'password' => $password,
    ];

    $factory = new Psr17Factory();

    $request = $factory
        ->createRequest('POST', $tokenUrl)
        ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
        ->withBody($factory->createStream(http_build_query($formFields)));

    $response = $httpClient->sendRequest($request);
    $payload = json_decode((string) $response->getBody(), true);

    if (!is_array($payload) || !array_key_exists('access_token', $payload) || !is_string($payload['access_token'])) {
        throw new RuntimeException(sprintf('Token endpoint did not return an access_token (HTTP %d).', $response->getStatusCode()));
    }

    return Client::withOAuth($payload['access_token'], $config);
}
