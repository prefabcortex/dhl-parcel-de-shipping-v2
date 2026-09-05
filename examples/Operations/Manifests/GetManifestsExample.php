<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\Manifests;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetManifestsUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\GetManifestsAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\SingleManifestResponse;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetManifestsQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;

final class GetManifestsExample
{
    /**
     * Return the manifest document for the specific date (abbreviated ISO8601 format YYYY-MM-DD). If no date is provided, the manifest for today will be returned. The manifest PDF document will list the shipments for your EKP, separated by billing numbers. Potentially, the document is large and response time will reflect this. <br />Additionally, the response contains a mapping of billing numbers to sheet numbers of the manifest and a mapping of shipment numbers to sheet numbers.<br />The call can be repeated as often as needed. Should a date be provided which is too old or lies within the future, HTTP 400 is returned.
     *
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     *   $client = Client::withBasicAuth(...); // withApiKey/withOAuth also available, see examples/Auth/
     *   $queryParameters = new GetManifestsQueryParameters(...);
     *   $headerParameters = new GetManifestsHeaderParameters(...);
     *   GetManifestsExample::getManifests($client, $queryParameters, $headerParameters);
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
    public static function getManifests(
        Client $client,
        GetManifestsQueryParameters $queryParameters,
        GetManifestsHeaderParameters $headerParameters
    ): SingleManifestResponse {
        $accept = [GetManifestsAccept::application_json, GetManifestsAccept::application_problem_json];

        return $client->manifests()->getManifests(
            $queryParameters,
            $headerParameters,
            $accept
        );
    }
}
