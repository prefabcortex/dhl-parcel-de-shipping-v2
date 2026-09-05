<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * The transport shape of one operation, implemented by the generated `Operation\*` classes and
 * consumed only by {@see ClientTrait}. Nothing a caller receives from this package is typed as an
 * `Operation`, so nothing outside it has a reason to name one — which is why the whole line, this
 * interface and its generated implementations alike, is disowned rather than promised. Left public,
 * every change to how a request is assembled would be reported as a break nobody could observe.
 *
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 *
 * @template-covariant TReturn
 */
interface Operation
{
    /**
     * Get payload for an operation (headers + body content).
     *
     * The widest type the implementations share, and since every exception a generated package
     * raises answers to it, also a complete one. A concrete operation names the exact classes on
     * its own `getPayload()`; here there is nothing narrower to say, and saying nothing is what
     * let a `JsonException` out of the request path without a single docblock on the way up
     * mentioning it.
     *
     * @throws ApiException
     */
    public function getPayload(StreamFactoryInterface $streamFactory): Payload;

    /**
     * Get the query string of an operation without the starting ? (like foo=foo&bar=bar).
     */
    public function getQueryString(): string;

    /**
     * Get the URI of an operation (like /foo-uri).
     */
    public function getUri(): string;

    /**
     * Get the HTTP method of an operation (like GET, POST, ...).
     */
    public function getMethod(): string;

    /**
     * Get the headers of an operation.
     *
     * @param array<string, list<string>> $baseHeaders
     *
     * @return array<string, list<string>>
     */
    public function getHeaders(array $baseHeaders = []): array;

    /**
     * Get security scopes of an operation.
     *
     * @return list<list<string>>
     */
    public function getSecurityRequirements(): array;

    /**
     * Parse and transform a PSR7 Response into a different object.
     *
     * @return TReturn
     *
     * @throws ApiException
     */
    public function parseResponse(ResponseInterface $response): mixed;
}
