<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\ShipmentsAndLabels;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\LabelDataResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\OrdersAccountDeleteAccept;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;

final class OrdersAccountDeleteExample
{
    /**
     * Delete one or more shipments created earlier. Deletion of shipments is only possible prior to them being manifested (closed out, 'Tagesabschluss'). The call will return HTTP 200 (single shipment) or 207 on success, with individual status elements for each shipment. Individual status elements are HTTP 200, 400. 400 will be returned when shipment does not exist (or was already deleted).
     *
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     *   $client = Client::withBasicAuth(...); // withApiKey/withOAuth also available, see examples/Auth/
     *   $queryParameters = new OrdersAccountDeleteQueryParameters(...);
     *   $headerParameters = new OrdersAccountDeleteHeaderParameters(...);
     *   OrdersAccountDeleteExample::ordersAccountDelete($client, $queryParameters, $headerParameters);
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws OrdersAccountDeleteBadRequestException
     * @throws OrdersAccountDeleteUnauthorizedException
     * @throws OrdersAccountDeleteTooManyRequestsException
     * @throws OrdersAccountDeleteInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    public static function ordersAccountDelete(
        Client $client,
        OrdersAccountDeleteQueryParameters $queryParameters,
        OrdersAccountDeleteHeaderParameters $headerParameters
    ): LabelDataResponse {
        $accept = [OrdersAccountDeleteAccept::application_json, OrdersAccountDeleteAccept::application_problem_json];

        return $client->shipmentsAndLabels()->ordersAccountDelete(
            $queryParameters,
            $headerParameters,
            $accept
        );
    }
}
