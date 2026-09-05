<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Api;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetLabelTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderNotFoundException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\GetOrderUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\OrdersAccountDeleteUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\CreateOrdersAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\GetLabelAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\GetOrderAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\LabelDataResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\OrdersAccountDeleteAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentOrderRequest;
use Prefabcortex\DhlParcelDeShippingV2\Operation\ShipmentsAndLabels\CreateOrders;
use Prefabcortex\DhlParcelDeShippingV2\Operation\ShipmentsAndLabels\GetLabel;
use Prefabcortex\DhlParcelDeShippingV2\Operation\ShipmentsAndLabels\GetOrder;
use Prefabcortex\DhlParcelDeShippingV2\Operation\ShipmentsAndLabels\OrdersAccountDelete;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetLabelQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\GetOrderQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\OrdersAccountDeleteQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Order and retrieve shipment labels.
 */
final readonly class ShipmentsAndLabelsApi
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Public download URL for shipment labels and documents. The URL is provided in the response of the POST /orders or GET /orders resources. The document is identified via the token query parameter. There is no additional authorization, the resource URL can be shared. Please protect the URL as needed. The call returns a PDF label.
     *
     * @param list<GetLabelAccept> $accept Accept content header application/pdf|application/problem+json
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
    public function getLabel(GetLabelQueryParameters $queryParameters, array $accept = []): string
    {
        return $this->client->executeOperation(new GetLabel($queryParameters, $accept));
    }

    /**
     * Public download URL for shipment labels and documents. The URL is provided in the response of the POST /orders or GET /orders resources. The document is identified via the token query parameter. There is no additional authorization, the resource URL can be shared. Please protect the URL as needed. The call returns a PDF label.
     *
     * @param list<GetLabelAccept> $accept Accept content header application/pdf|application/problem+json
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     */
    public function getLabelRaw(GetLabelQueryParameters $queryParameters, array $accept = []): ResponseInterface
    {
        return $this->client->executeRawOperation(new GetLabel($queryParameters, $accept));
    }

    /**
     * Delete one or more shipments created earlier. Deletion of shipments is only possible prior to them being manifested (closed out, 'Tagesabschluss'). The call will return HTTP 200 (single shipment) or 207 on success, with individual status elements for each shipment. Individual status elements are HTTP 200, 400. 400 will be returned when shipment does not exist (or was already deleted).
     *
     * @param list<OrdersAccountDeleteAccept> $accept Accept content header application/json|application/problem+json
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
    public function ordersAccountDelete(OrdersAccountDeleteQueryParameters $queryParameters, OrdersAccountDeleteHeaderParameters $headerParameters, array $accept = []): LabelDataResponse
    {
        return $this->client->executeOperation(new OrdersAccountDelete($queryParameters, $headerParameters, $accept));
    }

    /**
     * Delete one or more shipments created earlier. Deletion of shipments is only possible prior to them being manifested (closed out, 'Tagesabschluss'). The call will return HTTP 200 (single shipment) or 207 on success, with individual status elements for each shipment. Individual status elements are HTTP 200, 400. 400 will be returned when shipment does not exist (or was already deleted).
     *
     * @param list<OrdersAccountDeleteAccept> $accept Accept content header application/json|application/problem+json
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     */
    public function ordersAccountDeleteRaw(OrdersAccountDeleteQueryParameters $queryParameters, OrdersAccountDeleteHeaderParameters $headerParameters, array $accept = []): ResponseInterface
    {
        return $this->client->executeRawOperation(new OrdersAccountDelete($queryParameters, $headerParameters, $accept));
    }

    /**
     * Returns documents for existing shipment(s). The call accepts multiple shipment numbers and will provide sets of documents for those. The **format (PDF,ZPL)** and **method of delivery (URL, encoded, data)** can be selected for **all** shipments and labels in that call. You cannot chose one format and delivery method for one label and different for another label within the same call. You can also specify if you want regular labels, return labels, cod labels, or customsDoc. Any combination is possible.
     *
     * The call returns for each shipment number the status indicator and the selected labels and documents. If a label type (for example a cod label) does not exist for a shipment, it will not be returned. This is not an error. If you were sending multiple shipments, you will get an HTTP 207 response (multistatus) with detailed status for each shipment. Other standard HTTP response codes (200, 400, 401, 429, 500) are possible as well. Labels can be either provided as part of the response (base64 encoded for PDF, text for ZPL) or via URL link for view and download (PDF). Note that the format settings per query parameters apply to the shipping label. Retoure label paper type can be specified separately since a different printer may be used here. If requesting labels to be returned as URL for separate download, the URLs provided can be shared.
     *
     * @param list<GetOrderAccept> $accept Accept content header application/json|application/problem+json
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
    public function getOrder(GetOrderQueryParameters $queryParameters, GetOrderHeaderParameters $headerParameters, array $accept = []): LabelDataResponse
    {
        return $this->client->executeOperation(new GetOrder($queryParameters, $headerParameters, $accept));
    }

    /**
     * Returns documents for existing shipment(s). The call accepts multiple shipment numbers and will provide sets of documents for those. The **format (PDF,ZPL)** and **method of delivery (URL, encoded, data)** can be selected for **all** shipments and labels in that call. You cannot chose one format and delivery method for one label and different for another label within the same call. You can also specify if you want regular labels, return labels, cod labels, or customsDoc. Any combination is possible.
     *
     * The call returns for each shipment number the status indicator and the selected labels and documents. If a label type (for example a cod label) does not exist for a shipment, it will not be returned. This is not an error. If you were sending multiple shipments, you will get an HTTP 207 response (multistatus) with detailed status for each shipment. Other standard HTTP response codes (200, 400, 401, 429, 500) are possible as well. Labels can be either provided as part of the response (base64 encoded for PDF, text for ZPL) or via URL link for view and download (PDF). Note that the format settings per query parameters apply to the shipping label. Retoure label paper type can be specified separately since a different printer may be used here. If requesting labels to be returned as URL for separate download, the URLs provided can be shared.
     *
     * @param list<GetOrderAccept> $accept Accept content header application/json|application/problem+json
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     */
    public function getOrderRaw(GetOrderQueryParameters $queryParameters, GetOrderHeaderParameters $headerParameters, array $accept = []): ResponseInterface
    {
        return $this->client->executeRawOperation(new GetOrder($queryParameters, $headerParameters, $accept));
    }

    /**
     * This request is used to create one or more shipments and return corresponding shipment tracking numbers, labels, and documentation. Up to 30 shipments can be created in a single call.
     * #### Request
     * The selected products and corresponding billing numbers, as well as the desired services and package details are required to create a shipment. Each shipment can have a dedicated shipper address. The example request body contains sample values for most services.
     * #### Response
     * The request will return shipment tracking numbers and the applicable labels for each shipment. If multiple shipments have been included, an HTTP 207 response (multistatus) is returned and holds detailed status for each shipment. Other standard HTTP response codes (401, 500, 400, 200, 429) are possible, too. Labels can be either provided as part of the response (base64 encoded for PDF, text for ZPL) or via URL link for view and download. Note that the format settings per query parameters apply to the shipping label. It may also apply to other labels included, depending on the configuration of your account. Label paper for return shipments can be specified separately since a different printer may be used here. If requesting labels to be provided as URL for separate download, the URLs can be shared.
     * #### Validation
     * It is recommended to validate the request first prior to shipment creation by setting the `validate` query parameter to `true`. Especially, during development and test, it is recommended to perform this validation. This functionality supports both
     * * JSON schema validation (against this API description). During development and test, it is recommended to do this validation. JSON schema is available for local validation
     * * Dry run against the DHL backend.
     *
     * If this succeeds, actual shipment creation will also succeed.
     *
     * @param list<CreateOrdersAccept> $accept Accept content header application/json|application/problem+json
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws CreateOrdersBadRequestException
     * @throws CreateOrdersUnauthorizedException
     * @throws CreateOrdersTooManyRequestsException
     * @throws CreateOrdersInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    public function createOrders(ShipmentOrderRequest $requestBody, CreateOrdersQueryParameters $queryParameters, CreateOrdersHeaderParameters $headerParameters, array $accept = []): LabelDataResponse
    {
        return $this->client->executeOperation(new CreateOrders($requestBody, $queryParameters, $headerParameters, $accept));
    }

    /**
     * This request is used to create one or more shipments and return corresponding shipment tracking numbers, labels, and documentation. Up to 30 shipments can be created in a single call.
     * #### Request
     * The selected products and corresponding billing numbers, as well as the desired services and package details are required to create a shipment. Each shipment can have a dedicated shipper address. The example request body contains sample values for most services.
     * #### Response
     * The request will return shipment tracking numbers and the applicable labels for each shipment. If multiple shipments have been included, an HTTP 207 response (multistatus) is returned and holds detailed status for each shipment. Other standard HTTP response codes (401, 500, 400, 200, 429) are possible, too. Labels can be either provided as part of the response (base64 encoded for PDF, text for ZPL) or via URL link for view and download. Note that the format settings per query parameters apply to the shipping label. It may also apply to other labels included, depending on the configuration of your account. Label paper for return shipments can be specified separately since a different printer may be used here. If requesting labels to be provided as URL for separate download, the URLs can be shared.
     * #### Validation
     * It is recommended to validate the request first prior to shipment creation by setting the `validate` query parameter to `true`. Especially, during development and test, it is recommended to perform this validation. This functionality supports both
     * * JSON schema validation (against this API description). During development and test, it is recommended to do this validation. JSON schema is available for local validation
     * * Dry run against the DHL backend.
     *
     * If this succeeds, actual shipment creation will also succeed.
     *
     * @param list<CreateOrdersAccept> $accept Accept content header application/json|application/problem+json
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     */
    public function createOrdersRaw(ShipmentOrderRequest $requestBody, CreateOrdersQueryParameters $queryParameters, CreateOrdersHeaderParameters $headerParameters, array $accept = []): ResponseInterface
    {
        return $this->client->executeRawOperation(new CreateOrders($requestBody, $queryParameters, $headerParameters, $accept));
    }
}
