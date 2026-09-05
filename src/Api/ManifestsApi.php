<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Api;

use Prefabcortex\DhlParcelDeShippingV2\Client;
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
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\GetManifestsAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\ManifestsPostAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\MultipleManifestResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentManifestingRequest;
use Prefabcortex\DhlParcelDeShippingV2\Model\SingleManifestResponse;
use Prefabcortex\DhlParcelDeShippingV2\Operation\Manifests\GetManifests;
use Prefabcortex\DhlParcelDeShippingV2\Operation\Manifests\ManifestsPost;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Manifest shipments and retrieve daily manifest lists.
 */
final readonly class ManifestsApi
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Return the manifest document for the specific date (abbreviated ISO8601 format YYYY-MM-DD). If no date is provided, the manifest for today will be returned. The manifest PDF document will list the shipments for your EKP, separated by billing numbers. Potentially, the document is large and response time will reflect this. <br />Additionally, the response contains a mapping of billing numbers to sheet numbers of the manifest and a mapping of shipment numbers to sheet numbers.<br />The call can be repeated as often as needed. Should a date be provided which is too old or lies within the future, HTTP 400 is returned.
     *
     * @param list<GetManifestsAccept> $accept Accept content header application/json|application/problem+json
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws GetManifestsBadRequestException
     * @throws GetManifestsUnauthorizedException
     * @throws GetManifestsNotFoundException
     * @throws GetManifestsTooManyRequestsException
     * @throws GetManifestsInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    public function getManifests(GetManifestsQueryParameters $queryParameters, GetManifestsHeaderParameters $headerParameters, array $accept = []): SingleManifestResponse
    {
        return $this->client->executeOperation(new GetManifests($queryParameters, $headerParameters, $accept));
    }

    /**
     * Return the manifest document for the specific date (abbreviated ISO8601 format YYYY-MM-DD). If no date is provided, the manifest for today will be returned. The manifest PDF document will list the shipments for your EKP, separated by billing numbers. Potentially, the document is large and response time will reflect this. <br />Additionally, the response contains a mapping of billing numbers to sheet numbers of the manifest and a mapping of shipment numbers to sheet numbers.<br />The call can be repeated as often as needed. Should a date be provided which is too old or lies within the future, HTTP 400 is returned.
     *
     * @param list<GetManifestsAccept> $accept Accept content header application/json|application/problem+json
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     */
    public function getManifestsRaw(GetManifestsQueryParameters $queryParameters, GetManifestsHeaderParameters $headerParameters, array $accept = []): ResponseInterface
    {
        return $this->client->executeRawOperation(new GetManifests($queryParameters, $headerParameters, $accept));
    }

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
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws ManifestsPostBadRequestException
     * @throws ManifestsPostUnauthorizedException
     * @throws ManifestsPostTooManyRequestsException
     * @throws ManifestsPostInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    public function manifestsPost(ShipmentManifestingRequest $requestBody, ManifestsPostQueryParameters $queryParameters, ManifestsPostHeaderParameters $headerParameters, array $accept = []): MultipleManifestResponse
    {
        return $this->client->executeOperation(new ManifestsPost($requestBody, $queryParameters, $headerParameters, $accept));
    }

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
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     */
    public function manifestsPostRaw(ShipmentManifestingRequest $requestBody, ManifestsPostQueryParameters $queryParameters, ManifestsPostHeaderParameters $headerParameters, array $accept = []): ResponseInterface
    {
        return $this->client->executeRawOperation(new ManifestsPost($requestBody, $queryParameters, $headerParameters, $accept));
    }
}
