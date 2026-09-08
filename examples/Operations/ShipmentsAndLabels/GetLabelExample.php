<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\ShipmentsAndLabels;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\GetLabelAccept;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetLabelQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;

final class GetLabelExample
{
    /**
     * Public download URL for shipment labels and documents. The URL is provided in the response of
     * the POST /orders or GET /orders resources. The document is identified via the token query
     * parameter. There is no additional authorization, the resource URL can be shared. Please
     * protect the URL as needed. The call returns a PDF label.
     *
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     *   $client = Client::withBasicAuth(...); // withApiKey/withOAuth also available, see examples/Auth/
     *   $queryParameters = new GetLabelQueryParameters(...);
     *   GetLabelExample::getLabel($client, $queryParameters);
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws GetLabelNotFoundException
     * @throws GetLabelTooManyRequestsException
     * @throws GetLabelInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    public static function getLabel(
        Client $client,
        GetLabelQueryParameters $queryParameters,
    ): string {
        $accept = [GetLabelAccept::application_pdf, GetLabelAccept::application_problem_json];

        return $client->shipmentsAndLabels()->getLabel(
            $queryParameters,
            $accept,
        );
    }
}
