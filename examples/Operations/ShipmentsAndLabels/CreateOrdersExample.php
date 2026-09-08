<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\ShipmentsAndLabels;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\Commodity;
use Prefabcortex\DhlParcelDeShippingV2\Model\ContactAddress;
use Prefabcortex\DhlParcelDeShippingV2\Model\Country;
use Prefabcortex\DhlParcelDeShippingV2\Model\CreateOrdersAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\CustomsDetails;
use Prefabcortex\DhlParcelDeShippingV2\Model\CustomsDetailsExportType;
use Prefabcortex\DhlParcelDeShippingV2\Model\Dimensions;
use Prefabcortex\DhlParcelDeShippingV2\Model\DimensionsUom;
use Prefabcortex\DhlParcelDeShippingV2\Model\LabelDataResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\Product;
use Prefabcortex\DhlParcelDeShippingV2\Model\Shipment;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentDetails;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentOrderRequest;
use Prefabcortex\DhlParcelDeShippingV2\Model\Shipper;
use Prefabcortex\DhlParcelDeShippingV2\Model\Value;
use Prefabcortex\DhlParcelDeShippingV2\Model\ValueCurrency;
use Prefabcortex\DhlParcelDeShippingV2\Model\VAS;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASEndorsement;
use Prefabcortex\DhlParcelDeShippingV2\Model\Weight;
use Prefabcortex\DhlParcelDeShippingV2\Model\WeightUom;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersHeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Parameter\CreateOrdersQueryParameters;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;

final class CreateOrdersExample
{
    /**
     * This request is used to create one or more shipments and return corresponding shipment
     * tracking numbers, labels, and documentation. Up to 30 shipments can be created in a single
     * call.
     *
     * #### Request
     *
     * The selected products and corresponding billing numbers, as well as the desired services and
     * package details are required to create a shipment. Each shipment can have a dedicated shipper
     * address. The example request body contains sample values for most services.
     *
     * #### Response
     *
     * The request will return shipment tracking numbers and the applicable labels for each
     * shipment. If multiple shipments have been included, an HTTP 207 response (multistatus) is
     * returned and holds detailed status for each shipment. Other standard HTTP response codes
     * (401, 500, 400, 200, 429) are possible, too. Labels can be either provided as part of the
     * response (base64 encoded for PDF, text for ZPL) or via URL link for view and download. Note
     * that the format settings per query parameters apply to the shipping label. It may also apply
     * to other labels included, depending on the configuration of your account. Label paper for
     * return shipments can be specified separately since a different printer may be used here. If
     * requesting labels to be provided as URL for separate download, the URLs can be shared.
     *
     * #### Validation
     *
     * It is recommended to validate the request first prior to shipment creation by setting the
     * `validate` query parameter to `true`. Especially, during development and test, it is
     * recommended to perform this validation. This functionality supports both JSON schema
     * validation (against this API description). During development and test, it is recommended to
     * do this validation. JSON schema is available for local validation Dry run against the DHL
     * backend.
     *
     * If this succeeds, actual shipment creation will also succeed.
     *
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     * Request body: pass the result of one of buildDHLPaket(), buildDHLPaketInternational(),
     * buildDHLPaketInternationalWithCustoms(), buildDHLKleinpaket(),
     * buildWarenpostInternationalWithCustoms().
     *
     *   $client = Client::withBasicAuth(...); // withApiKey/withOAuth also available, see examples/Auth/
     *   $queryParameters = new CreateOrdersQueryParameters(...);
     *   $headerParameters = new CreateOrdersHeaderParameters(...);
     *   CreateOrdersExample::createOrders($client, CreateOrdersExample::buildDHLPaket(), $queryParameters, $headerParameters);
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
    public static function createOrders(
        Client $client,
        ShipmentOrderRequest $requestBody,
        CreateOrdersQueryParameters $queryParameters,
        CreateOrdersHeaderParameters $headerParameters,
    ): LabelDataResponse {
        $accept = [CreateOrdersAccept::application_json, CreateOrdersAccept::application_problem_json];

        return $client->shipmentsAndLabels()->createOrders(
            $requestBody,
            $queryParameters,
            $headerParameters,
            $accept,
        );
    }

    /**
     * DHL Paket (V01PAK).
     *
     * Order example for DHL Paket (V01PAK)
     */
    public static function buildDHLPaket(): ShipmentOrderRequest
    {
        $shipper = Shipper::create(
            'My Online Shop GmbH',
            'Sträßchensweg 10',
            'Bonn',
            Country::DEU,
        )
            ->withPostalCode('53113')
            ->withEmail('max@mustermann.de');
        $consignee = ContactAddress::create(
            'Maria Musterfrau',
            'Kurt-Schumacher-Str. 20',
            'Bonn',
            Country::DEU,
        )
            ->withPostalCode('53113')
            ->withPhone('+49 987654321')
            ->withEmail('maria@musterfrau.de');
        $weight = Weight::create(
            WeightUom::g,
            500.0,
        );
        $dim = Dimensions::create(
            DimensionsUom::mm,
            100,
            200,
            150,
        );
        $details = ShipmentDetails::create($weight)
            ->withDim($dim);
        $shipment = Shipment::create()
            ->withProduct(Product::V01PAK)
            ->withBillingNumber('33333333330102')
            ->withRefNo('Order No. 1234')
            ->withShipper($shipper)
            ->withConsignee($consignee)
            ->withDetails($details);

        return ShipmentOrderRequest::create(
            'STANDARD_GRUPPENPROFIL',
            [$shipment],
        );
    }

    /**
     * DHL Paket International (V53WPAK).
     *
     * Order example for DHL Paket International (V53WPAK)
     */
    public static function buildDHLPaketInternational(): ShipmentOrderRequest
    {
        $shipper = Shipper::create(
            'My Online Shop GmbH',
            'Sträßchensweg 10',
            'Bonn',
            Country::DEU,
        )
            ->withPostalCode('53113')
            ->withEmail('max@mustermann.de');
        $consignee = ContactAddress::create(
            'Jan Vermeer',
            'Museumstraat',
            'Amsterdam',
            Country::NLD,
        )
            ->withAddressHouse('1')
            ->withAdditionalAddressInformation1('2. Floor')
            ->withPostalCode('1071 AA')
            ->withPhone('+31 888888888')
            ->withEmail('jan@vermeer.com');
        $weight = Weight::create(
            WeightUom::g,
            500.0,
        );
        $dim = Dimensions::create(
            DimensionsUom::mm,
            100,
            200,
            150,
        );
        $details = ShipmentDetails::create($weight)
            ->withDim($dim);
        $shipment = Shipment::create()
            ->withProduct(Product::V53WPAK)
            ->withBillingNumber('33333333335301')
            ->withRefNo('Order No. 1234')
            ->withShipper($shipper)
            ->withConsignee($consignee)
            ->withDetails($details);

        return ShipmentOrderRequest::create(
            'STANDARD_GRUPPENPROFIL',
            [$shipment],
        );
    }

    /**
     * DHL Paket International (V53WPAK) with customs.
     *
     * Order example for DHL Paket International (V53WPAK) with customs
     */
    public static function buildDHLPaketInternationalWithCustoms(): ShipmentOrderRequest
    {
        $shipper = Shipper::create(
            'My Online Shop GmbH',
            'Sträßchensweg 10',
            'Bonn',
            Country::DEU,
        )
            ->withPostalCode('53113')
            ->withEmail('max@mustermann.de');
        $consignee = ContactAddress::create(
            'Joe Black',
            '10 Downing Street',
            'London',
            Country::GBR,
        )
            ->withAdditionalAddressInformation1('2. Floor')
            ->withPostalCode('SW1A 1AA')
            ->withPhone('+44 123456789')
            ->withEmail('joe@black.uk');
        $weight = Weight::create(
            WeightUom::g,
            500.0,
        );
        $dim = Dimensions::create(
            DimensionsUom::mm,
            100,
            200,
            150,
        );
        $details = ShipmentDetails::create($weight)
            ->withDim($dim);
        $services = VAS::create()
            ->withEndorsement(VASEndorsement::RETURN);
        $postalCharges = Value::create(
            ValueCurrency::EUR,
            1.0,
        );
        $itemValue = Value::create(
            ValueCurrency::EUR,
            10.0,
        );
        $itemWeight = Weight::create(
            WeightUom::g,
            400.0,
        );
        $commodity = Commodity::create(
            'Red T-Shirt',
            1,
            $itemValue,
            $itemWeight,
        )
            ->withCountryOfOrigin(Country::FRA)
            ->withHsCode('123456');
        $customs = CustomsDetails::create(
            CustomsDetailsExportType::COMMERCIAL_GOODS,
            $postalCharges,
            [$commodity],
        );
        $shipment = Shipment::create()
            ->withProduct(Product::V53WPAK)
            ->withBillingNumber('33333333335301')
            ->withRefNo('Order No. 1234')
            ->withShipper($shipper)
            ->withConsignee($consignee)
            ->withDetails($details)
            ->withServices($services)
            ->withCustoms($customs);

        return ShipmentOrderRequest::create(
            'STANDARD_GRUPPENPROFIL',
            [$shipment],
        );
    }

    /**
     * DHL Kleinpaket (V62KP).
     *
     * Order example for DHL Kleinpaket (V62KP)
     */
    public static function buildDHLKleinpaket(): ShipmentOrderRequest
    {
        $shipper = Shipper::create(
            'My Online Shop GmbH',
            'Sträßchensweg 10',
            'Bonn',
            Country::DEU,
        )
            ->withPostalCode('53113')
            ->withEmail('max@mustermann.de');
        $consignee = ContactAddress::create(
            'Maria Musterfrau',
            'Kurt-Schumacher-Str. 20',
            'Bonn',
            Country::DEU,
        )
            ->withPostalCode('53113')
            ->withPhone('+49 987654321')
            ->withEmail('maria@musterfrau.de');
        $weight = Weight::create(
            WeightUom::g,
            500.0,
        );
        $dim = Dimensions::create(
            DimensionsUom::cm,
            1,
            10,
            15,
        );
        $details = ShipmentDetails::create($weight)
            ->withDim($dim);
        $shipment = Shipment::create()
            ->withProduct(Product::V62KP)
            ->withBillingNumber('33333333336201')
            ->withRefNo('Order No. 1234')
            ->withShipper($shipper)
            ->withConsignee($consignee)
            ->withDetails($details);

        return ShipmentOrderRequest::create(
            'STANDARD_GRUPPENPROFIL',
            [$shipment],
        );
    }

    /**
     * Warenpost International (V66WPI) with customs.
     *
     * Order example for Warenpost International (V66WPI) with customs
     */
    public static function buildWarenpostInternationalWithCustoms(): ShipmentOrderRequest
    {
        $shipper = Shipper::create(
            'My Online Shop GmbH',
            'Sträßchensweg 10',
            'Bonn',
            Country::DEU,
        )
            ->withPostalCode('53113')
            ->withEmail('max@mustermann.de');
        $consignee = ContactAddress::create(
            'Joe Black',
            '42 Street',
            'London',
            Country::GBR,
        )
            ->withAdditionalAddressInformation1('2. Floor')
            ->withPostalCode('SW1A 1AA')
            ->withPhone('+44 123456789')
            ->withEmail('joe@black.uk');
        $weight = Weight::create(
            WeightUom::g,
            500.0,
        );
        $dim = Dimensions::create(
            DimensionsUom::cm,
            1,
            10,
            15,
        );
        $details = ShipmentDetails::create($weight)
            ->withDim($dim);
        $services = VAS::create()
            ->withEndorsement(VASEndorsement::RETURN);
        $postalCharges = Value::create(
            ValueCurrency::EUR,
            1.0,
        );
        $itemValue = Value::create(
            ValueCurrency::EUR,
            10.0,
        );
        $itemWeight = Weight::create(
            WeightUom::g,
            300.0,
        );
        $commodity = Commodity::create(
            'Item 1',
            1,
            $itemValue,
            $itemWeight,
        )
            ->withCountryOfOrigin(Country::FRA)
            ->withHsCode('123456');
        $customs = CustomsDetails::create(
            CustomsDetailsExportType::PRESENT,
            $postalCharges,
            [$commodity],
        );
        $shipment = Shipment::create()
            ->withProduct(Product::V66WPI)
            ->withBillingNumber('33333333336601')
            ->withRefNo('Order No. 1234')
            ->withShipper($shipper)
            ->withConsignee($consignee)
            ->withDetails($details)
            ->withServices($services)
            ->withCustoms($customs);

        return ShipmentOrderRequest::create(
            'STANDARD_GRUPPENPROFIL',
            [$shipment],
        );
    }
}
