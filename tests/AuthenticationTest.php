<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\CannedResponse;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\RecordingHttpClient;

use function sprintf;

/**
 * Each way of building a client, called against an operation it can authenticate.
 *
 * The assertions look only at what the credentials put on the wire: a header, a query parameter, an
 * `Authorization` line. Where a requirement names several schemes, all of their marks are checked
 * on the same request — half a signature is no signature.
 *
 * A factory that no operation in this description requires has no test here: nothing it signs could
 * be observed.
 */
final class AuthenticationTest extends TestCase
{
    private const string BASE_URL = 'https://auth-test.invalid';

    /** @throws InvalidArgumentException */
    public function testWithBasicAuthSignsTheRequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::withBasicAuth(
                'placeholder-credential',
                'placeholder-credential',
                ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient),
            );
            $client->shipmentsAndLabels()->ordersAccountDeleteRaw(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'withBasicAuth', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertSame(
                'Basic cGxhY2Vob2xkZXItY3JlZGVudGlhbDpwbGFjZWhvbGRlci1jcmVkZW50aWFs',
                $request->getHeaderLine('Authorization'),
                'the request went out without the credentials of the "BasicAuth" scheme',
            );
        }
    }

    /** @throws InvalidArgumentException */
    public function testWithApiKeySignsTheRequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::withApiKey(
                'placeholder-credential',
                ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient),
            );
            $client->shipmentsAndLabels()->ordersAccountDeleteRaw(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'withApiKey', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertSame(
                'placeholder-credential',
                $request->getHeaderLine('dhl-api-key'),
                'the request went out without the credentials of the "ApiKey" scheme',
            );
        }
    }

    /** @throws InvalidArgumentException */
    public function testWithOAuthSignsTheRequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::withOAuth(
                'placeholder-credential',
                ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient),
            );
            $client->shipmentsAndLabels()->ordersAccountDeleteRaw(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'withOAuth', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertSame(
                'Bearer placeholder-credential',
                $request->getHeaderLine('Authorization'),
                'the request went out without the credentials of the "OAuth2" scheme',
            );
        }
    }
}
