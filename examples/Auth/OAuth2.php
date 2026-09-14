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
 * The client authenticates with client_id and client_secret in the request body
 * (client_secret_post, RFC 6749 §2.3.1). If the provider expects them in an HTTP Basic
 * header instead, send them there and leave them out of the body.
 *
 * The token expires after the number of seconds the token response names in expires_in, and
 * the returned Client keeps using it regardless. This example neither caches nor renews it:
 * call it again for a fresh Client once that time has passed.
 *
 * Usage: set OAUTH2_USERNAME, OAUTH2_PASSWORD, OAUTH2_CLIENT_ID and OAUTH2_CLIENT_SECRET in the environment, then
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
    $clientId = getenv('OAUTH2_CLIENT_ID');
    $clientSecret = getenv('OAUTH2_CLIENT_SECRET');

    if (false === $username || false === $password || false === $clientId || false === $clientSecret) {
        throw new RuntimeException('Set OAUTH2_USERNAME, OAUTH2_PASSWORD, OAUTH2_CLIENT_ID and OAUTH2_CLIENT_SECRET before running this example.');
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
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ];

    $factory = new Psr17Factory();

    $request = $factory
        ->createRequest('POST', $tokenUrl)
        ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
        ->withBody($factory->createStream(http_build_query($formFields)));

    $response = $httpClient->sendRequest($request);
    $status = $response->getStatusCode();
    $payload = json_decode((string) $response->getBody(), true);

    // A refused token request names its reason in `error` and may explain it in
    // `error_description` (RFC 6749 §5.2).
    if ($status < 200 || $status >= 300) {
        $reason = 'no error code';
        if (is_array($payload) && array_key_exists('error', $payload) && is_string($payload['error'])) {
            $reason = $payload['error'];
        }
        if (is_array($payload) && array_key_exists('error_description', $payload) && is_string($payload['error_description'])) {
            $reason .= ' — ' . $payload['error_description'];
        }

        throw new RuntimeException(sprintf('Token endpoint refused the request (HTTP %d): %s', $status, $reason));
    }

    if (!is_array($payload) || !array_key_exists('access_token', $payload) || !is_string($payload['access_token'])) {
        throw new RuntimeException(sprintf('Token endpoint did not return an access_token (HTTP %d).', $status));
    }

    return Client::withOAuth($payload['access_token'], $config);
}
