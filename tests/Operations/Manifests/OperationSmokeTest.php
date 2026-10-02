<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests\Operations\Manifests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\CannedResponse;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\ModelFixtures;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\RecordingHttpClient;
use RuntimeException;

use function sprintf;

/**
 * Every operation called once, against a client that records the request instead of sending it.
 *
 * Nothing leaves the process and no credentials are needed: PSR-18 is one method, so the client is
 * stood in for. What runs is everything up to the wire — the URI assembled, the query string
 * encoded, the body serialised. The client signs nothing.
 *
 * What is watched is the request: its method, and its path up to the first placeholder. These calls
 * go through the `…Raw()` methods, which hand the response back unparsed, so the canned answer
 * never has to match a status or content type from the description — an answer invented from that
 * document would say nothing about a client built from the same one.
 */
final class OperationSmokeTest extends TestCase
{
    private const string BASE_URL = 'https://smoke-test.invalid';

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->manifests()->getManifestsRaw(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'getManifests', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertSame('GET', $request->getMethod(), 'the request went out with another HTTP method');
            self::assertStringStartsWith(
                self::BASE_URL . '/manifests',
                (string) $request->getUri(),
                'the request did not go to the operation\'s path below the configured base URL',
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->manifests()->manifestsPostRaw(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'manifestsPost', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertSame('POST', $request->getMethod(), 'the request went out with another HTTP method');
            self::assertStringStartsWith(
                self::BASE_URL . '/manifests',
                (string) $request->getUri(),
                'the request did not go to the operation\'s path below the configured base URL',
            );
        }
    }
}
