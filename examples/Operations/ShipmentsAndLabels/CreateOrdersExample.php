<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\ShipmentsAndLabels;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersBadRequestException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\CreateOrdersUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedResponseException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ResponseValidationException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedContentTypeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\Commodity;
use Prefabcortex\DhlParcelDeShippingV2\Model\ContactAddress;
use Prefabcortex\DhlParcelDeShippingV2\Model\Country;
use Prefabcortex\DhlParcelDeShippingV2\Model\CreateOrdersAcceptHeader;
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
     *   $client = Client::withBasicAuth($username, $password, $config); // withApiKey/withOAuth also available, see examples/Auth/
     *   $queryParameters = new CreateOrdersQueryParameters();
     *   $headerParameters = new CreateOrdersHeaderParameters();
     *   CreateOrdersExample::createOrders($client, CreateOrdersExample::buildDHLPaket(), $queryParameters, $headerParameters);
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ResponseValidationException
     * @throws MalformedResponseException
     * @throws CreateOrdersBadRequestException
     * @throws CreateOrdersUnauthorizedException
     * @throws CreateOrdersTooManyRequestsException
     * @throws CreateOrdersInternalServerErrorException
     * @throws UnexpectedContentTypeException
     * @throws UnexpectedStatusCodeException
     */
    public static function createOrders(
        Client $client,
        ShipmentOrderRequest $requestBody,
        CreateOrdersQueryParameters $queryParameters,
        CreateOrdersHeaderParameters $headerParameters,
    ): LabelDataResponse {
        $accept = [CreateOrdersAcceptHeader::application_json, CreateOrdersAcceptHeader::application_problem_json];

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
        $shipper = Shipper::builder(
            // name1
            'My Online Shop GmbH',
            // addressStreet
            'Sträßchensweg 10',
            // city
            'Bonn',
            // country
            Country::DEU,
        )
            ->setPostalCode('53113')
            ->setEmail('max@mustermann.de')
            ->build();
        $consignee = ContactAddress::builder(
            // name1
            'Maria Musterfrau',
            // addressStreet
            'Kurt-Schumacher-Str. 20',
            // city
            'Bonn',
            // country
            Country::DEU,
        )
            ->setPostalCode('53113')
            ->setPhone('+49 987654321')
            ->setEmail('maria@musterfrau.de')
            ->build();
        $weight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();
        $dim = Dimensions::builder(
            // uom
            DimensionsUom::mm,
            // height
            100,
            // length
            200,
            // width
            150,
        )->build();
        $details = ShipmentDetails::builder($weight)
            ->setDim($dim)
            ->build();
        $shipment = Shipment::builder()
            ->setProduct(Product::V01PAK)
            ->setBillingNumber('33333333330102')
            ->setRefNo('Order No. 1234')
            ->setShipper($shipper)
            ->setConsignee($consignee)
            ->setDetails($details)
            ->build();

        return ShipmentOrderRequest::builder(
            // profile
            'STANDARD_GRUPPENPROFIL',
            // shipments
            [$shipment],
        )->build();
    }

    /**
     * DHL Paket International (V53WPAK).
     *
     * Order example for DHL Paket International (V53WPAK)
     */
    public static function buildDHLPaketInternational(): ShipmentOrderRequest
    {
        $shipper = Shipper::builder(
            // name1
            'My Online Shop GmbH',
            // addressStreet
            'Sträßchensweg 10',
            // city
            'Bonn',
            // country
            Country::DEU,
        )
            ->setPostalCode('53113')
            ->setEmail('max@mustermann.de')
            ->build();
        $consignee = ContactAddress::builder(
            // name1
            'Jan Vermeer',
            // addressStreet
            'Museumstraat',
            // city
            'Amsterdam',
            // country
            Country::NLD,
        )
            ->setAddressHouse('1')
            ->setAdditionalAddressInformation1('2. Floor')
            ->setPostalCode('1071 AA')
            ->setPhone('+31 888888888')
            ->setEmail('jan@vermeer.com')
            ->build();
        $weight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();
        $dim = Dimensions::builder(
            // uom
            DimensionsUom::mm,
            // height
            100,
            // length
            200,
            // width
            150,
        )->build();
        $details = ShipmentDetails::builder($weight)
            ->setDim($dim)
            ->build();
        $shipment = Shipment::builder()
            ->setProduct(Product::V53WPAK)
            ->setBillingNumber('33333333335301')
            ->setRefNo('Order No. 1234')
            ->setShipper($shipper)
            ->setConsignee($consignee)
            ->setDetails($details)
            ->build();

        return ShipmentOrderRequest::builder(
            // profile
            'STANDARD_GRUPPENPROFIL',
            // shipments
            [$shipment],
        )->build();
    }

    /**
     * DHL Paket International (V53WPAK) with customs.
     *
     * Order example for DHL Paket International (V53WPAK) with customs
     */
    public static function buildDHLPaketInternationalWithCustoms(): ShipmentOrderRequest
    {
        $shipper = Shipper::builder(
            // name1
            'My Online Shop GmbH',
            // addressStreet
            'Sträßchensweg 10',
            // city
            'Bonn',
            // country
            Country::DEU,
        )
            ->setPostalCode('53113')
            ->setEmail('max@mustermann.de')
            ->build();
        $consignee = ContactAddress::builder(
            // name1
            'Joe Black',
            // addressStreet
            '10 Downing Street',
            // city
            'London',
            // country
            Country::GBR,
        )
            ->setAdditionalAddressInformation1('2. Floor')
            ->setPostalCode('SW1A 1AA')
            ->setPhone('+44 123456789')
            ->setEmail('joe@black.uk')
            ->build();
        $weight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();
        $dim = Dimensions::builder(
            // uom
            DimensionsUom::mm,
            // height
            100,
            // length
            200,
            // width
            150,
        )->build();
        $details = ShipmentDetails::builder($weight)
            ->setDim($dim)
            ->build();
        $services = VAS::builder()
            ->setEndorsement(VASEndorsement::RETURN)
            ->build();
        $postalCharges = Value::builder(
            // currency
            ValueCurrency::EUR,
            // value
            1.0,
        )->build();
        $itemValue = Value::builder(
            // currency
            ValueCurrency::EUR,
            // value
            10.0,
        )->build();
        $itemWeight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            400.0,
        )->build();
        $commodity = Commodity::builder(
            // itemDescription
            'Red T-Shirt',
            // packagedQuantity
            1,
            // itemValue
            $itemValue,
            // itemWeight
            $itemWeight,
        )
            ->setCountryOfOrigin(Country::FRA)
            ->setHsCode('123456')
            ->build();
        $customs = CustomsDetails::builder(
            // exportType
            CustomsDetailsExportType::COMMERCIAL_GOODS,
            // postalCharges
            $postalCharges,
            // items
            [$commodity],
        )->build();
        $shipment = Shipment::builder()
            ->setProduct(Product::V53WPAK)
            ->setBillingNumber('33333333335301')
            ->setRefNo('Order No. 1234')
            ->setShipper($shipper)
            ->setConsignee($consignee)
            ->setDetails($details)
            ->setServices($services)
            ->setCustoms($customs)
            ->build();

        return ShipmentOrderRequest::builder(
            // profile
            'STANDARD_GRUPPENPROFIL',
            // shipments
            [$shipment],
        )->build();
    }

    /**
     * DHL Kleinpaket (V62KP).
     *
     * Order example for DHL Kleinpaket (V62KP)
     */
    public static function buildDHLKleinpaket(): ShipmentOrderRequest
    {
        $shipper = Shipper::builder(
            // name1
            'My Online Shop GmbH',
            // addressStreet
            'Sträßchensweg 10',
            // city
            'Bonn',
            // country
            Country::DEU,
        )
            ->setPostalCode('53113')
            ->setEmail('max@mustermann.de')
            ->build();
        $consignee = ContactAddress::builder(
            // name1
            'Maria Musterfrau',
            // addressStreet
            'Kurt-Schumacher-Str. 20',
            // city
            'Bonn',
            // country
            Country::DEU,
        )
            ->setPostalCode('53113')
            ->setPhone('+49 987654321')
            ->setEmail('maria@musterfrau.de')
            ->build();
        $weight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();
        $dim = Dimensions::builder(
            // uom
            DimensionsUom::cm,
            // height
            1,
            // length
            10,
            // width
            15,
        )->build();
        $details = ShipmentDetails::builder($weight)
            ->setDim($dim)
            ->build();
        $shipment = Shipment::builder()
            ->setProduct(Product::V62KP)
            ->setBillingNumber('33333333336201')
            ->setRefNo('Order No. 1234')
            ->setShipper($shipper)
            ->setConsignee($consignee)
            ->setDetails($details)
            ->build();

        return ShipmentOrderRequest::builder(
            // profile
            'STANDARD_GRUPPENPROFIL',
            // shipments
            [$shipment],
        )->build();
    }

    /**
     * Warenpost International (V66WPI) with customs.
     *
     * Order example for Warenpost International (V66WPI) with customs
     */
    public static function buildWarenpostInternationalWithCustoms(): ShipmentOrderRequest
    {
        $shipper = Shipper::builder(
            // name1
            'My Online Shop GmbH',
            // addressStreet
            'Sträßchensweg 10',
            // city
            'Bonn',
            // country
            Country::DEU,
        )
            ->setPostalCode('53113')
            ->setEmail('max@mustermann.de')
            ->build();
        $consignee = ContactAddress::builder(
            // name1
            'Joe Black',
            // addressStreet
            '42 Street',
            // city
            'London',
            // country
            Country::GBR,
        )
            ->setAdditionalAddressInformation1('2. Floor')
            ->setPostalCode('SW1A 1AA')
            ->setPhone('+44 123456789')
            ->setEmail('joe@black.uk')
            ->build();
        $weight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();
        $dim = Dimensions::builder(
            // uom
            DimensionsUom::cm,
            // height
            1,
            // length
            10,
            // width
            15,
        )->build();
        $details = ShipmentDetails::builder($weight)
            ->setDim($dim)
            ->build();
        $services = VAS::builder()
            ->setEndorsement(VASEndorsement::RETURN)
            ->build();
        $postalCharges = Value::builder(
            // currency
            ValueCurrency::EUR,
            // value
            1.0,
        )->build();
        $itemValue = Value::builder(
            // currency
            ValueCurrency::EUR,
            // value
            10.0,
        )->build();
        $itemWeight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            300.0,
        )->build();
        $commodity = Commodity::builder(
            // itemDescription
            'Item 1',
            // packagedQuantity
            1,
            // itemValue
            $itemValue,
            // itemWeight
            $itemWeight,
        )
            ->setCountryOfOrigin(Country::FRA)
            ->setHsCode('123456')
            ->build();
        $customs = CustomsDetails::builder(
            // exportType
            CustomsDetailsExportType::PRESENT,
            // postalCharges
            $postalCharges,
            // items
            [$commodity],
        )->build();
        $shipment = Shipment::builder()
            ->setProduct(Product::V66WPI)
            ->setBillingNumber('33333333336601')
            ->setRefNo('Order No. 1234')
            ->setShipper($shipper)
            ->setConsignee($consignee)
            ->setDetails($details)
            ->setServices($services)
            ->setCustoms($customs)
            ->build();

        return ShipmentOrderRequest::builder(
            // profile
            'STANDARD_GRUPPENPROFIL',
            // shipments
            [$shipment],
        )->build();
    }
}
