<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Operation\ShipmentsAndLabels;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Http\BaseOperationTrait;
use Prefabcortex\DhlParcelDeShippingV2\Http\ContentType;
use Prefabcortex\DhlParcelDeShippingV2\Http\JsonBody;
use Prefabcortex\DhlParcelDeShippingV2\Http\Operation;
use Prefabcortex\DhlParcelDeShippingV2\Http\OperationTrait;
use Prefabcortex\DhlParcelDeShippingV2\Http\Payload;
use Prefabcortex\DhlParcelDeShippingV2\Http\QueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Model\GetLabelAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\RequestStatus;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetLabelQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidatorTrait;
use Prefabcortex\DhlParcelDeShippingV2\Validator\RequestStatusConstraint;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function array_map;

/**
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 *
 * @implements Operation<string>
 */
final class GetLabel implements Operation
{
    use BaseOperationTrait;
    use OperationTrait;
    use ValidatorTrait;
    /** @var list<GetLabelAccept> */
    protected array $accept;
    private readonly GetLabelQueryParameters $queryParameters;

    /**
     * Public download URL for shipment labels and documents. The URL is provided in the response of the POST /orders or GET /orders resources. The document is identified via the token query parameter. There is no additional authorization, the resource URL can be shared. Please protect the URL as needed. The call returns a PDF label.
     *
     * @param list<GetLabelAccept> $accept Accept content header application/pdf|application/problem+json
     */
    public function __construct(GetLabelQueryParameters $queryParameters, array $accept)
    {
        $this->queryParameters = $queryParameters;
        $this->accept = $accept;
    }

    #[Override]
    public function getMethod(): string
    {
        return 'GET';
    }

    #[Override]
    public function getUri(): string
    {
        return '/labels';
    }

    #[Override]
    public function getPayload(StreamFactoryInterface $streamFactory): Payload
    {
        return new Payload([], '');
    }

    /** @return array<string, list<string>> */
    public function getExtraHeaders(): array
    {
        if ($this->accept === []) {
            return ['Accept' => ['application/pdf', 'application/problem+json']];
        }

        return ['Accept' => array_map(static fn (GetLabelAccept $acceptValue): string => $acceptValue->value, $this->accept)];
    }

    protected function getQueryParameters(): QueryParameters
    {
        return $this->queryParameters->toQueryParameters();
    }

    /**
     * {@inheritdoc}.
     *
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws GetLabelNotFoundException
     * @throws GetLabelTooManyRequestsException
     * @throws GetLabelInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    #[Override]
    protected function transformResponseBody(ResponseInterface $response, ContentType $contentType): string
    {
        $status = $response->getStatusCode();
        $body = (string) $response->getBody();
        if (200 === $status && $contentType->is('application/pdf')) {
            return $body;
        }
        if (404 === $status && $contentType->is('application/problem+json')) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, RequestStatusConstraint::constraints());
            throw new GetLabelNotFoundException(RequestStatus::fromArray($typedData), $response, $body);
        }
        if (429 === $status && $contentType->is('application/problem+json')) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, RequestStatusConstraint::constraints());
            throw new GetLabelTooManyRequestsException(RequestStatus::fromArray($typedData), $response, $body);
        }
        if (500 === $status && $contentType->is('application/problem+json')) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, RequestStatusConstraint::constraints());
            throw new GetLabelInternalServerErrorException(RequestStatus::fromArray($typedData), $response, $body);
        }
        throw new UnexpectedStatusCodeException($response, $body);
    }

    /**
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws GetLabelNotFoundException
     * @throws GetLabelTooManyRequestsException
     * @throws GetLabelInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    #[Override]
    public function parseResponse(ResponseInterface $response): string
    {
        $contentType = ContentType::fromHeader($response->getHeader('Content-Type')[0] ?? '');

        return $this->transformResponseBody($response, $contentType);
    }

    /** @return list<list<string>> */
    #[Override]
    public function getSecurityRequirements(): array
    {
        return [];
    }
}
