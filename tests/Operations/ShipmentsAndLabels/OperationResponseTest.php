<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests\Operations\ShipmentsAndLabels;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\ClientConfig;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedContentTypeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Http\JsonBody;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetLabelQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteQueryParameters;
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
    public function testShipmentsAndLabelsGetLabelReads404AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(404, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getLabel(new GetLabelQueryParameters('smoke-test'));
            self::fail(sprintf('%s did not throw GetLabelNotFoundException for its %d response', 'getLabel', 404));
        } catch (GetLabelNotFoundException $exception) {
            self::assertSame(404, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getLabel', 404, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetLabelReads429AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(429, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getLabel(new GetLabelQueryParameters('smoke-test'));
            self::fail(
                sprintf('%s did not throw GetLabelTooManyRequestsException for its %d response', 'getLabel', 429),
            );
        } catch (GetLabelTooManyRequestsException $exception) {
            self::assertSame(429, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getLabel', 429, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetLabelReads500AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(500, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getLabel(new GetLabelQueryParameters('smoke-test'));
            self::fail(
                sprintf('%s did not throw GetLabelInternalServerErrorException for its %d response', 'getLabel', 500),
            );
        } catch (GetLabelInternalServerErrorException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getLabel', 500, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetLabelRejectsAnUndeclaredStatus(): void
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
            $client->shipmentsAndLabels()->getLabel(new GetLabelQueryParameters('smoke-test'));
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedStatusCodeException for a %d response it cannot read',
                    'getLabel',
                    599,
                ),
            );
        } catch (UnexpectedStatusCodeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getLabel', 599, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetLabelRejectsAnUndeclaredContentType(): void
    {
        $httpClient = new RecordingHttpClient(
            CannedResponse::answering(
                404,
                'text/html',
                '<html><body>Served by something in front of the API</body></html>',
            ),
        );
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getLabel(new GetLabelQueryParameters('smoke-test'));
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedContentTypeException for a %d response it cannot read',
                    'getLabel',
                    404,
                ),
            );
        } catch (UnexpectedContentTypeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getLabel', 404, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteReads200(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(200, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 200, $error::class, $error->getMessage()),
            );
        }
        self::assertEquals(ModelFixtures::buildLabelDataResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteReads207(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(207, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 207, $error::class, $error->getMessage()),
            );
        }
        self::assertEquals(ModelFixtures::buildLabelDataResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteReads400AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(400, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw OrdersAccountDeleteBadRequestException for its %d response',
                    'ordersAccountDelete',
                    400,
                ),
            );
        } catch (OrdersAccountDeleteBadRequestException $exception) {
            self::assertSame(400, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildLabelDataResponse(), $exception->getLabelDataResponse());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 400, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteReads401AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(401, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw OrdersAccountDeleteUnauthorizedException for its %d response',
                    'ordersAccountDelete',
                    401,
                ),
            );
        } catch (OrdersAccountDeleteUnauthorizedException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 401, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteReads429AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(429, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw OrdersAccountDeleteTooManyRequestsException for its %d response',
                    'ordersAccountDelete',
                    429,
                ),
            );
        } catch (OrdersAccountDeleteTooManyRequestsException $exception) {
            self::assertSame(429, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 429, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteReads500AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(500, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw OrdersAccountDeleteInternalServerErrorException for its %d response',
                    'ordersAccountDelete',
                    500,
                ),
            );
        } catch (OrdersAccountDeleteInternalServerErrorException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 500, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteRejectsAnUndeclaredStatus(): void
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
            $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedStatusCodeException for a %d response it cannot read',
                    'ordersAccountDelete',
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
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 599, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsOrdersAccountDeleteRejectsAnUndeclaredContentType(): void
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
            $client->shipmentsAndLabels()->ordersAccountDelete(
                new OrdersAccountDeleteQueryParameters('smoke-test', 'smoke-test'),
                new OrdersAccountDeleteHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedContentTypeException for a %d response it cannot read',
                    'ordersAccountDelete',
                    200,
                ),
            );
        } catch (UnexpectedContentTypeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(
                sprintf('%s answered %d with %s: %s', 'ordersAccountDelete', 200, $error::class, $error->getMessage()),
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderReads200(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(200, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 200, $error::class, $error->getMessage()));
        }
        self::assertEquals(ModelFixtures::buildLabelDataResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderReads207(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(207, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 207, $error::class, $error->getMessage()));
        }
        self::assertEquals(ModelFixtures::buildLabelDataResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderReads400AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(400, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
            self::fail(sprintf('%s did not throw GetOrderBadRequestException for its %d response', 'getOrder', 400));
        } catch (GetOrderBadRequestException $exception) {
            self::assertSame(400, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildLabelDataResponse(), $exception->getLabelDataResponse());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 400, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderReads401AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(401, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
            self::fail(sprintf('%s did not throw GetOrderUnauthorizedException for its %d response', 'getOrder', 401));
        } catch (GetOrderUnauthorizedException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 401, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderReads404AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(404, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
            self::fail(sprintf('%s did not throw GetOrderNotFoundException for its %d response', 'getOrder', 404));
        } catch (GetOrderNotFoundException $exception) {
            self::assertSame(404, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 404, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderReads429AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(429, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw GetOrderTooManyRequestsException for its %d response', 'getOrder', 429),
            );
        } catch (GetOrderTooManyRequestsException $exception) {
            self::assertSame(429, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 429, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderReads500AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(500, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw GetOrderInternalServerErrorException for its %d response', 'getOrder', 500),
            );
        } catch (GetOrderInternalServerErrorException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 500, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderRejectsAnUndeclaredStatus(): void
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
            $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedStatusCodeException for a %d response it cannot read',
                    'getOrder',
                    599,
                ),
            );
        } catch (UnexpectedStatusCodeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 599, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsGetOrderRejectsAnUndeclaredContentType(): void
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
            $client->shipmentsAndLabels()->getOrder(
                new GetOrderQueryParameters([]),
                new GetOrderHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedContentTypeException for a %d response it cannot read',
                    'getOrder',
                    200,
                ),
            );
        } catch (UnexpectedContentTypeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'getOrder', 200, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersReads200(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(200, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 200, $error::class, $error->getMessage()));
        }
        self::assertEquals(ModelFixtures::buildLabelDataResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersReads207(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(207, 'application/json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $result = $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 207, $error::class, $error->getMessage()));
        }
        self::assertEquals(ModelFixtures::buildLabelDataResponse(), $result);
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersReads400AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildLabelDataResponse());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(400, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw CreateOrdersBadRequestException for its %d response', 'createOrders', 400),
            );
        } catch (CreateOrdersBadRequestException $exception) {
            self::assertSame(400, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildLabelDataResponse(), $exception->getLabelDataResponse());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 400, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersReads401AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(401, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
            self::fail(
                sprintf('%s did not throw CreateOrdersUnauthorizedException for its %d response', 'createOrders', 401),
            );
        } catch (CreateOrdersUnauthorizedException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 401, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersReads429AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(429, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw CreateOrdersTooManyRequestsException for its %d response',
                    'createOrders',
                    429,
                ),
            );
        } catch (CreateOrdersTooManyRequestsException $exception) {
            self::assertSame(429, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 429, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersReads500AsProblemJson(): void
    {
        $body = JsonBody::encode(ModelFixtures::buildRequestStatus());
        $httpClient = new RecordingHttpClient(CannedResponse::answering(500, 'application/problem+json', $body));
        $client = Client::create(ClientConfig::forBaseUrl(self::BASE_URL)->withHttpClient($httpClient));
        try {
            $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw CreateOrdersInternalServerErrorException for its %d response',
                    'createOrders',
                    500,
                ),
            );
        } catch (CreateOrdersInternalServerErrorException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
            self::assertEquals(ModelFixtures::buildRequestStatus(), $exception->getRequestStatus());
            self::assertSame($body, $exception->getRawResponse());
            self::assertSame($body, $exception->getResponse()->getBody()->getContents());
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 500, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersRejectsAnUndeclaredStatus(): void
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
            $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedStatusCodeException for a %d response it cannot read',
                    'createOrders',
                    599,
                ),
            );
        } catch (UnexpectedStatusCodeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 599, $error::class, $error->getMessage()));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws MalformedDataException
     * @throws RuntimeException
     */
    public function testShipmentsAndLabelsCreateOrdersRejectsAnUndeclaredContentType(): void
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
            $client->shipmentsAndLabels()->createOrders(
                ModelFixtures::buildShipmentOrderRequest(),
                new CreateOrdersQueryParameters(),
                new CreateOrdersHeaderParameters(),
                [],
            );
            self::fail(
                sprintf(
                    '%s did not throw UnexpectedContentTypeException for a %d response it cannot read',
                    'createOrders',
                    200,
                ),
            );
        } catch (UnexpectedContentTypeException $exception) {
            self::assertSame(
                '<html><body>Served by something in front of the API</body></html>',
                $exception->getResponse()->getBody()->getContents(),
            );
        } catch (ApiException $error) {
            self::fail(sprintf('%s answered %d with %s: %s', 'createOrders', 200, $error::class, $error->getMessage()));
        }
    }
}
