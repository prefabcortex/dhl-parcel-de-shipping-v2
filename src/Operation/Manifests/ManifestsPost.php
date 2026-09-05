<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Operation\Manifests;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Http\BaseOperationTrait;
use Prefabcortex\DhlParcelDeShippingV2\Http\ContentType;
use Prefabcortex\DhlParcelDeShippingV2\Http\HeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Http\JsonBody;
use Prefabcortex\DhlParcelDeShippingV2\Http\Operation;
use Prefabcortex\DhlParcelDeShippingV2\Http\OperationTrait;
use Prefabcortex\DhlParcelDeShippingV2\Http\Payload;
use Prefabcortex\DhlParcelDeShippingV2\Http\QueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Model\LabelDataResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\ManifestsPostAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\MultipleManifestResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\RequestStatus;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentManifestingRequest;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidatorTrait;
use Prefabcortex\DhlParcelDeShippingV2\Validator\LabelDataResponseConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\MultipleManifestResponseConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\RequestStatusConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipmentManifestingRequestConstraint;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function array_map;

/**
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 *
 * @implements Operation<MultipleManifestResponse>
 */
final class ManifestsPost implements Operation
{
    use BaseOperationTrait;
    use OperationTrait;
    use ValidatorTrait;
    private readonly ShipmentManifestingRequest $body;
    /** @var list<ManifestsPostAccept> */
    protected array $accept;
    private readonly ManifestsPostQueryParameters $queryParameters;
    private readonly ManifestsPostHeaderParameters $headerParameters;

    /**
     * Shipments are normally ''closed out'' at a fixed time of the day (such as 6 pm, configured by EKP/account) for the date provided as shipDate in the create call.
     * <br />This call allows forcing the closeout for sets of shipments earlier. This will also override the original shipDate. Afterwards, the shipment cannot be changed and the shipment labels cannot be queried anymore (however they may remain cached for limited duration).
     * Once a shipment has been closed, then calling closeout for the same shipment will result in a warning. The same warning will also be returned if the automatic closeout happened prior to the call. It is however possible to add new shipments, they will be manifested as well and be part of the day's manifest.
     * <br />Note on billing: The manifesting step has billing implications. Some products (Parcel International partially) are billed based on the shipment data available to DHL at the end of the day. All other products (including DHL Paket Standard) are billed based on production data. For more details, please contact your account representative.
     *
     * #### Request
     * It's changing the status of the shipment, so parameters are provided in the body or as query parameter.
     * * ''profile'' attribute (request body parameter) - defines the user group profile. A user group is permitted to specific billing numbers. Shipments are only closed out if they belong to a billing number that the user group profile is entitled to use. This attribute is mandatory. Please use the standard user group profile ''STANDARD_GRUPPENPROFIL'' if no dedicated user group profile is available.
     * * ''billingNumber'' attribute (query parameter) - defines the billing number for which shipments shall be closed out. If a billing number is set, then only the shipments of that billing number are closed out. In that case no list of specific shipment numbers needs to be passed.
     * * ''shipmentNumbers'' attribute (request body parameter) - lists the specific shipping numbers of the shipments that shall be closed out.
     * If all shipments shall be closed, the query parameter ''all'' needs to be set to ''true''. In that case neither a billing number nor a list of shipment numbers need to be passed in the request.
     *
     * #### Response
     * * Closing status for each shipment
     *
     * @param list<ManifestsPostAccept> $accept Accept content header application/json|application/problem+json
     */
    public function __construct(ShipmentManifestingRequest $requestBody, ManifestsPostQueryParameters $queryParameters, ManifestsPostHeaderParameters $headerParameters, array $accept)
    {
        $this->body = $requestBody;
        $this->queryParameters = $queryParameters;
        $this->headerParameters = $headerParameters;
        $this->accept = $accept;
    }

    #[Override]
    public function getMethod(): string
    {
        return 'POST';
    }

    #[Override]
    public function getUri(): string
    {
        return '/manifests';
    }

    /**
     * @throws MalformedDataException
     * @throws ValidationException
     */
    #[Override]
    public function getPayload(StreamFactoryInterface $streamFactory): Payload
    {
        $data = $this->body->toArray();
        $this->validate($data, ShipmentManifestingRequestConstraint::constraints());

        return new Payload(['Content-Type' => ['application/json']], JsonBody::encode($data));
    }

    /** @return array<string, list<string>> */
    public function getExtraHeaders(): array
    {
        if ($this->accept === []) {
            return ['Accept' => ['application/json', 'application/problem+json']];
        }

        return ['Accept' => array_map(static fn (ManifestsPostAccept $acceptValue): string => $acceptValue->value, $this->accept)];
    }

    protected function getQueryParameters(): QueryParameters
    {
        return $this->queryParameters->toQueryParameters();
    }

    protected function getHeaderParameters(): HeaderParameters
    {
        return $this->headerParameters->toHeaderParameters();
    }

    /**
     * {@inheritdoc}.
     *
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws ManifestsPostBadRequestException
     * @throws ManifestsPostUnauthorizedException
     * @throws ManifestsPostTooManyRequestsException
     * @throws ManifestsPostInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    #[Override]
    protected function transformResponseBody(ResponseInterface $response, ContentType $contentType): MultipleManifestResponse
    {
        $status = $response->getStatusCode();
        $body = (string) $response->getBody();
        if (207 === $status && $contentType->isAnyOf(['application/json', 'application/problem+json'])) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, MultipleManifestResponseConstraint::constraints());

            return MultipleManifestResponse::fromArray($typedData);
        }
        if (400 === $status && $contentType->is('application/problem+json')) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, LabelDataResponseConstraint::constraints());
            throw new ManifestsPostBadRequestException(LabelDataResponse::fromArray($typedData), $response, $body);
        }
        if (401 === $status && $contentType->is('application/problem+json')) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, RequestStatusConstraint::constraints());
            throw new ManifestsPostUnauthorizedException(RequestStatus::fromArray($typedData), $response, $body);
        }
        if (429 === $status && $contentType->is('application/problem+json')) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, RequestStatusConstraint::constraints());
            throw new ManifestsPostTooManyRequestsException(RequestStatus::fromArray($typedData), $response, $body);
        }
        if (500 === $status && $contentType->is('application/problem+json')) {
            $typedData = JsonBody::toArray($body);
            $this->validate($typedData, RequestStatusConstraint::constraints());
            throw new ManifestsPostInternalServerErrorException(RequestStatus::fromArray($typedData), $response, $body);
        }
        throw new UnexpectedStatusCodeException($response, $body);
    }

    /**
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws ManifestsPostBadRequestException
     * @throws ManifestsPostUnauthorizedException
     * @throws ManifestsPostTooManyRequestsException
     * @throws ManifestsPostInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    #[Override]
    public function parseResponse(ResponseInterface $response): MultipleManifestResponse
    {
        $contentType = ContentType::fromHeader($response->getHeader('Content-Type')[0] ?? '');

        return $this->transformResponseBody($response, $contentType);
    }

    /** @return list<list<string>> */
    #[Override]
    public function getSecurityRequirements(): array
    {
        return [['ApiKey'], ['BasicAuth'], ['OAuth2']];
    }
}
