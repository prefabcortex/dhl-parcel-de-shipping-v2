<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests\Operations\General;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedContentTypeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Http\JsonBody;
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
    public function testGeneralRootGetReads200(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildServiceInformation());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(200, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->general()->rootGet();
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'rootGet', 200, $error::class, $error->getMessage()));
        }
        self::assertEquals(ModelFixtures::buildServiceInformation(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testGeneralRootGetReads401AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(401, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->general()->rootGet();
            self::fail(sprintf('%s did not throw RootGetUnauthorizedException for its %d response', 'rootGet', 401));
        } catch (RootGetUnauthorizedException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'rootGet', 401, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testGeneralRootGetReads429AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(429, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->general()->rootGet();
            self::fail(sprintf('%s did not throw RootGetTooManyRequestsException for its %d response', 'rootGet', 429));
        } catch (RootGetTooManyRequestsException $exception) {
            self::assertSame(429, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'rootGet', 429, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testGeneralRootGetReads500AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(500, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->general()->rootGet();
            self::fail(
                sprintf('%s did not throw RootGetInternalServerErrorException for its %d response', 'rootGet', 500),
            );
        } catch (RootGetInternalServerErrorException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'rootGet', 500, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testGeneralRootGetRejectsAnUndeclaredStatus(): void
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
            $client->general()->rootGet();
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedStatusCodeException for a %d response it cannot read',
                    'rootGet',
                    599,
                ),
            );
        } catch (UnexpectedStatusCodeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'rootGet', 599, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testGeneralRootGetRejectsAnUndeclaredContentType(): void
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
            $client->general()->rootGet();
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedContentTypeException for a %d response it cannot read',
                    'rootGet',
                    200,
                ),
            );
        } catch (UnexpectedContentTypeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'rootGet', 200, $error::class, $error->getMessage()));
        }
    }
}
