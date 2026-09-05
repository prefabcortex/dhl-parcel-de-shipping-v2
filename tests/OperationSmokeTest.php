<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetLabelQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\CannedResponse;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\ModelFixtures;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\RecordingHttpClient;

use function sprintf;

/**
 * Every operation called once, against a client that records the request instead of sending it.
 *
 * Nothing leaves the process and no credentials are needed: PSR-18 is one method, so the
 * client is stood in for. What runs is everything up to the wire — the URI assembled, the
 * query string encoded, the body serialised, the authenticator applied.
 *
 * The assertions are deliberately thin. These calls go through the `…Raw()` methods, which
 * hand the response back unparsed, so the canned answer never has to match a status or
 * content type from the description — an answer invented from that document would say
 * nothing about a client built from the same one. What is watched is the request.
 */
final class OperationSmokeTest extends TestCase
{
    private const string BASE_URL = 'https://smoke-test.invalid';

    /** @throws InvalidArgumentException */
    public function testGeneralRootGetBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->general()->rootGetRaw([]);
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'rootGet', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertNotSame('', $request->getMethod(), 'the request went out without an HTTP method');
            self::assertStringStartsWith(self::BASE_URL, (string) $request->getUri(), 'the request did not go to the configured base URL');
        }
    }

    /** @throws InvalidArgumentException */
    public function testShipmentsAndLabelsGetLabelBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->shipmentsAndLabels()->getLabelRaw(new GetLabelQueryParameters('smoke-test'), []);
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'getLabel', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertNotSame('', $request->getMethod(), 'the request went out without an HTTP method');
            self::assertStringStartsWith(self::BASE_URL, (string) $request->getUri(), 'the request did not go to the configured base URL');
        }
    }

    /** @throws InvalidArgumentException */
    public function testShipmentsAndLabelsOrdersAccountDeleteBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->shipmentsAndLabels()->ordersAccountDeleteRaw(new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'), new OrdersAccountDeleteHeaderParameters(), []);
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'ordersAccountDelete', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertNotSame('', $request->getMethod(), 'the request went out without an HTTP method');
            self::assertStringStartsWith(self::BASE_URL, (string) $request->getUri(), 'the request did not go to the configured base URL');
        }
    }

    /** @throws InvalidArgumentException */
    public function testShipmentsAndLabelsGetOrderBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->shipmentsAndLabels()->getOrderRaw(new GetOrderQueryParameters([]), new GetOrderHeaderParameters(), []);
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'getOrder', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertNotSame('', $request->getMethod(), 'the request went out without an HTTP method');
            self::assertStringStartsWith(self::BASE_URL, (string) $request->getUri(), 'the request did not go to the configured base URL');
        }
    }

    /** @throws InvalidArgumentException */
    public function testShipmentsAndLabelsCreateOrdersBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->shipmentsAndLabels()->createOrdersRaw(ModelFixtures::buildShipmentOrderRequest(), new CreateOrdersQueryParameters(), new CreateOrdersHeaderParameters(), []);
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'createOrders', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertNotSame('', $request->getMethod(), 'the request went out without an HTTP method');
            self::assertStringStartsWith(self::BASE_URL, (string) $request->getUri(), 'the request did not go to the configured base URL');
        }
    }

    /** @throws InvalidArgumentException */
    public function testManifestsGetManifestsBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->manifests()->getManifestsRaw(new GetManifestsQueryParameters(), new GetManifestsHeaderParameters(), []);
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'getManifests', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertNotSame('', $request->getMethod(), 'the request went out without an HTTP method');
            self::assertStringStartsWith(self::BASE_URL, (string) $request->getUri(), 'the request did not go to the configured base URL');
        }
    }

    /** @throws InvalidArgumentException */
    public function testManifestsManifestsPostBuildsARequest(): void
    {
        $httpClient = new RecordingHttpClient(CannedResponse::empty());
        try {
            $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
            $client->manifests()->manifestsPostRaw(ModelFixtures::buildShipmentManifestingRequest(), new ManifestsPostQueryParameters(), new ManifestsPostHeaderParameters(), []);
        } catch (ApiException $error) {
            self::fail(sprintf('%s could not be called: %s', 'manifestsPost', $error->getMessage()));
        }
        $requests = $httpClient->getRequests();
        self::assertCount(1, $requests, 'the operation did not hand exactly one request to the HTTP client');
        foreach ($requests as $request) {
            self::assertNotSame('', $request->getMethod(), 'the request went out without an HTTP method');
            self::assertStringStartsWith(self::BASE_URL, (string) $request->getUri(), 'the request did not go to the configured base URL');
        }
    }
}
