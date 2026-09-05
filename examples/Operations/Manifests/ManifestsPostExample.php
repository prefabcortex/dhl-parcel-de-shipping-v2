<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\Manifests;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ManifestsPostUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\ManifestsPostAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\MultipleManifestResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentManifestingRequest;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\ManifestsPostQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;

final class ManifestsPostExample
{
    /**
     * Shipments are normally ''closed out'' at a fixed time of the day (such as 6 pm, configured by EKP/account) for the date provided as shipDate in the create call. <br />This call allows forcing the closeout for sets of shipments earlier. This will also override the original shipDate. Afterwards, the shipment cannot be changed and the shipment labels cannot be queried anymore (however they may remain cached for limited duration). Once a shipment has been closed, then calling closeout for the same shipment will result in a warning. The same warning will also be returned if the automatic closeout happened prior to the call. It is however possible to add new shipments, they will be manifested as well and be part of the day's manifest. <br />Note on billing: The manifesting step has billing implications. Some products (Parcel International partially) are billed based on the shipment data available to DHL at the end of the day. All other products (including DHL Paket Standard) are billed based on production data. For more details, please contact your account representative.  #### Request It's changing the status of the shipment, so parameters are provided in the body or as query parameter. ''profile'' attribute (request body parameter) - defines the user group profile. A user group is permitted to specific billing numbers. Shipments are only closed out if they belong to a billing number that the user group profile is entitled to use. This attribute is mandatory. Please use the standard user group profile ''STANDARD_GRUPPENPROFIL'' if no dedicated user group profile is available. ''billingNumber'' attribute (query parameter) - defines the billing number for which shipments shall be closed out. If a billing number is set, then only the shipments of that billing number are closed out. In that case no list of specific shipment numbers needs to be passed. ''shipmentNumbers'' attribute (request body parameter) - lists the specific shipping numbers of the shipments that shall be closed out. If all shipments shall be closed, the query parameter ''all'' needs to be set to ''true''. In that case neither a billing number nor a list of shipment numbers need to be passed in the request.  #### Response Closing status for each shipment.
     *
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     *   $client = Client::withBasicAuth(...); // withApiKey/withOAuth also available, see examples/Auth/
     *   $queryParameters = new ManifestsPostQueryParameters(...);
     *   $headerParameters = new ManifestsPostHeaderParameters(...);
     *   ManifestsPostExample::manifestsPost($client, ManifestsPostExample::buildExampleRequestBody(), $queryParameters, $headerParameters);
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
    public static function manifestsPost(
        Client $client,
        ShipmentManifestingRequest $requestBody,
        ManifestsPostQueryParameters $queryParameters,
        ManifestsPostHeaderParameters $headerParameters
    ): MultipleManifestResponse {
        $accept = [ManifestsPostAccept::application_json, ManifestsPostAccept::application_problem_json];

        return $client->manifests()->manifestsPost(
            $requestBody,
            $queryParameters,
            $headerParameters,
            $accept
        );
    }

    /**
     * NOTE: This is a structurally valid skeleton generated from the OpenAPI schema
     * (required properties, plus optional properties that carry a spec `example:`).
     * oneOf/anyOf alternatives always pick the first option. It does NOT encode
     * business rules the spec only documents in prose (e.g. "either X or Y must be
     * set"). Review the values and the API documentation before production use.
     */
    public static function buildExampleRequestBody(): ShipmentManifestingRequest
    {
        return ShipmentManifestingRequest::create('REPLACE_ME');
    }
}
