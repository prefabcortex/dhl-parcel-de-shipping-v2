<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\ShipmentsAndLabels;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\GetOrderAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\LabelDataResponse;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;

final class GetOrderExample
{
    /**
     * Returns documents for existing shipment(s). The call accepts multiple shipment numbers and
     * will provide sets of documents for those. The **format (PDF,ZPL)** and **method of delivery
     * (URL, encoded, data)** can be selected for **all** shipments and labels in that call. You
     * cannot chose one format and delivery method for one label and different for another label
     * within the same call. You can also specify if you want regular labels, return labels, cod
     * labels, or customsDoc. Any combination is possible.
     *
     * The call returns for each shipment number the status indicator and the selected labels and
     * documents. If a label type (for example a cod label) does not exist for a shipment, it will
     * not be returned. This is not an error. If you were sending multiple shipments, you will get
     * an HTTP 207 response (multistatus) with detailed status for each shipment. Other standard
     * HTTP response codes (200, 400, 401, 429, 500) are possible as well. Labels can be either
     * provided as part of the response (base64 encoded for PDF, text for ZPL) or via URL link for
     * view and download (PDF). Note that the format settings per query parameters apply to the
     * shipping label. Retoure label paper type can be specified separately since a different
     * printer may be used here. If requesting labels to be returned as URL for separate download,
     * the URLs provided can be shared.
     *
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     *   $client = Client::withBasicAuth(...); // withApiKey/withOAuth also available, see examples/Auth/
     *   $queryParameters = new GetOrderQueryParameters(...);
     *   $headerParameters = new GetOrderHeaderParameters(...);
     *   GetOrderExample::getOrder($client, $queryParameters, $headerParameters);
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws GetOrderBadRequestException
     * @throws GetOrderUnauthorizedException
     * @throws GetOrderNotFoundException
     * @throws GetOrderTooManyRequestsException
     * @throws GetOrderInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    public static function getOrder(
        Client $client,
        GetOrderQueryParameters $queryParameters,
        GetOrderHeaderParameters $headerParameters,
    ): LabelDataResponse {
        $accept = [GetOrderAccept::application_json, GetOrderAccept::application_problem_json];

        return $client->shipmentsAndLabels()->getOrder(
            $queryParameters,
            $headerParameters,
            $accept,
        );
    }
}
