<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function array_merge;

/**
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
trait BaseOperationTrait
{
    abstract public function getMethod(): string;

    abstract public function getPayload(StreamFactoryInterface $streamFactory): Payload;

    abstract public function getUri(): string;

    /** @return list<list<string>> */
    abstract public function getSecurityRequirements(): array;

    /**
     * Transforms the raw HTTP response body into this operation's declared return type.
     * Generated overrides always emit an inheritdoc tag here, so this description must stay non-empty.
     */
    abstract protected function transformResponseBody(ResponseInterface $response, ContentType $contentType): mixed;

    /** @return array<string, list<string>> */
    protected function getExtraHeaders(): array
    {
        return [];
    }

    /**
     * The operation's query parameters. Overridden by operations that have any, returning the
     * collection their typed parameter object builds from its own properties — which is why
     * nothing here has to inspect a value to find out what it is.
     */
    protected function getQueryParameters(): QueryParameters
    {
        return QueryParameters::none();
    }

    /**
     *  The operation's header parameters; see {@see getQueryParameters()}.
     */
    protected function getHeaderParameters(): HeaderParameters
    {
        return HeaderParameters::none();
    }

    final public function getQueryString(): string
    {
        return $this->transformQueryParameters($this->getQueryParameters())->encode();
    }

    /**
     * @param array<string, list<string>> $baseHeaders
     *
     * @return array<string, list<string>>
     */
    final public function getHeaders(array $baseHeaders = []): array
    {
        return array_merge($this->getExtraHeaders(), $baseHeaders, $this->getHeaderParameters()->toHeaderMap());
    }

    /**
     * Applies any configured `custom-query-resolver` transformations. No-op unless overridden —
     * only operations with a configured transformer for one of their query parameters override
     * this.
     */
    protected function transformQueryParameters(QueryParameters $queryParameters): QueryParameters
    {
        return $queryParameters;
    }
}
