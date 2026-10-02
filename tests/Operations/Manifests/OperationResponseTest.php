<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests\Operations\Manifests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedContentTypeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Http\JsonBody;
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
 * Every response an operation reads, answered once through the method that reads it.
 *
 * A recorded client answers with the status and content type of one branch and a body built from
 * the model fixtures; the test checks that the model comes back, or the declared exception with the
 * response still readable. A status no response declares and a declared status under the wrong
 * content type are answered too.
 *
 * What this cannot show: that the service sends these documents. They come from the same
 * description the client came from, so this proves the package reads what it promises.
 */
final class OperationResponseTest extends TestCase
{
    private const string BASE_URL = 'https://response-test.invalid';

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsReads200(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildSingleManifestResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(200, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 200, $error::class, $error->getMessage()));
        }
        self::assertEquals(ModelFixtures::buildSingleManifestResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsReads400AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(400, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw GetManifestsBadRequestException for its %d response', 'getManifests', 400),
            );
        } catch (GetManifestsBadRequestException $exception) {
            self::assertSame(400, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildLabelDataResponse(), $exception->getLabelDataResponse());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 400, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsReads401AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(401, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw GetManifestsUnauthorizedException for its %d response', 'getManifests', 401),
            );
        } catch (GetManifestsUnauthorizedException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 401, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsReads404AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(404, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw GetManifestsNotFoundException for its %d response', 'getManifests', 404),
            );
        } catch (GetManifestsNotFoundException $exception) {
            self::assertSame(404, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 404, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsReads429AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(429, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw GetManifestsTooManyRequestsException for its %d response',
                    'getManifests',
                    429,
                ),
            );
        } catch (GetManifestsTooManyRequestsException $exception) {
            self::assertSame(429, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 429, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsReads500AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(500, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw GetManifestsInternalServerErrorException for its %d response',
                    'getManifests',
                    500,
                ),
            );
        } catch (GetManifestsInternalServerErrorException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 500, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsRejectsAnUndeclaredStatus(): void
    {
        $httpClient = new RecordingHttpClient(
            CannedResponse::answering(
                599,
                'text/html',
                '<html><body>Served by something in front of the API</body></html>',
            ),
        );
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedStatusCodeException for a %d response it cannot read',
                    'getManifests',
                    599,
                ),
            );
        } catch (UnexpectedStatusCodeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 599, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsGetManifestsRejectsAnUndeclaredContentType(): void
    {
        $httpClient = new RecordingHttpClient(
            CannedResponse::answering(
                200,
                'text/html',
                '<html><body>Served by something in front of the API</body></html>',
            ),
        );
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->getManifests(
                new GetManifestsQueryParameters(),
                new GetManifestsHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedContentTypeException for a %d response it cannot read',
                    'getManifests',
                    200,
                ),
            );
        } catch (UnexpectedContentTypeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getManifests', 200, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostReads207(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildMultipleManifestResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(207, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->manifests()->manifestsPost(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'manifestsPost', 207, $error::class, $error->getMessage()),
            );
        }
        self::assertEquals(ModelFixtures::buildMultipleManifestResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostReads400AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(400, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->manifestsPost(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw ManifestsPostBadRequestException for its %d response', 'manifestsPost', 400),
            );
        } catch (ManifestsPostBadRequestException $exception) {
            self::assertSame(400, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildLabelDataResponse(), $exception->getLabelDataResponse());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'manifestsPost', 400, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostReads401AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(401, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->manifestsPost(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw ManifestsPostUnauthorizedException for its %d response',
                    'manifestsPost',
                    401,
                ),
            );
        } catch (ManifestsPostUnauthorizedException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'manifestsPost', 401, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostReads429AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(429, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->manifestsPost(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw ManifestsPostTooManyRequestsException for its %d response',
                    'manifestsPost',
                    429,
                ),
            );
        } catch (ManifestsPostTooManyRequestsException $exception) {
            self::assertSame(429, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'manifestsPost', 429, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostReads500AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(500, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->manifestsPost(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw ManifestsPostInternalServerErrorException for its %d response',
                    'manifestsPost',
                    500,
                ),
            );
        } catch (ManifestsPostInternalServerErrorException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'manifestsPost', 500, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostRejectsAnUndeclaredStatus(): void
    {
        $httpClient = new RecordingHttpClient(
            CannedResponse::answering(
                599,
                'text/html',
                '<html><body>Served by something in front of the API</body></html>',
            ),
        );
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->manifestsPost(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedStatusCodeException for a %d response it cannot read',
                    'manifestsPost',
                    599,
                ),
            );
        } catch (UnexpectedStatusCodeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'manifestsPost', 599, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testManifestsManifestsPostRejectsAnUndeclaredContentType(): void
    {
        $httpClient = new RecordingHttpClient(
            CannedResponse::answering(
                207,
                'text/html',
                '<html><body>Served by something in front of the API</body></html>',
            ),
        );
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->manifests()->manifestsPost(
                ModelFixtures::buildShipmentManifestingRequest(),
                new ManifestsPostQueryParameters(),
                new ManifestsPostHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedContentTypeException for a %d response it cannot read',
                    'manifestsPost',
                    207,
                ),
            );
        } catch (UnexpectedContentTypeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'manifestsPost', 207, $error::class, $error->getMessage()),
            );
        }
    }
}
