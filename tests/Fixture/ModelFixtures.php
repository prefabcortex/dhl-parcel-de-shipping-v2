<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture;

use Prefabcortex\DhlParcelDeShippingV2\Model\BankAccount;
use Prefabcortex\DhlParcelDeShippingV2\Model\BillingNoToSheetNo;
use Prefabcortex\DhlParcelDeShippingV2\Model\Commodity;
use Prefabcortex\DhlParcelDeShippingV2\Model\ContactAddress;
use Prefabcortex\DhlParcelDeShippingV2\Model\Country;
use Prefabcortex\DhlParcelDeShippingV2\Model\CustomsDetails;
use Prefabcortex\DhlParcelDeShippingV2\Model\CustomsDetailsExportType;
use Prefabcortex\DhlParcelDeShippingV2\Model\Dimensions;
use Prefabcortex\DhlParcelDeShippingV2\Model\DimensionsUom;
use Prefabcortex\DhlParcelDeShippingV2\Model\Document;
use Prefabcortex\DhlParcelDeShippingV2\Model\DocumentFileFormat;
use Prefabcortex\DhlParcelDeShippingV2\Model\DocumentPrintFormat;
use Prefabcortex\DhlParcelDeShippingV2\Model\LabelDataResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\Locker;
use Prefabcortex\DhlParcelDeShippingV2\Model\MultipleManifestResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\POBox;
use Prefabcortex\DhlParcelDeShippingV2\Model\PostOffice;
use Prefabcortex\DhlParcelDeShippingV2\Model\RequestStatus;
use Prefabcortex\DhlParcelDeShippingV2\Model\ResponseItem;
use Prefabcortex\DhlParcelDeShippingV2\Model\SelfNormalizingModel;
use Prefabcortex\DhlParcelDeShippingV2\Model\ServiceInformation;
use Prefabcortex\DhlParcelDeShippingV2\Model\ServiceInformationAmp;
use Prefabcortex\DhlParcelDeShippingV2\Model\ServiceInformationBackend;
use Prefabcortex\DhlParcelDeShippingV2\Model\Shipment;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentDetails;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentManifestingRequest;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentNoToSheetNo;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipmentOrderRequest;
use Prefabcortex\DhlParcelDeShippingV2\Model\Shipper;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShipperReference;
use Prefabcortex\DhlParcelDeShippingV2\Model\ShortResponseItem;
use Prefabcortex\DhlParcelDeShippingV2\Model\SingleManifestResponse;
use Prefabcortex\DhlParcelDeShippingV2\Model\ValidationMessageItem;
use Prefabcortex\DhlParcelDeShippingV2\Model\Value;
use Prefabcortex\DhlParcelDeShippingV2\Model\ValueCurrency;
use Prefabcortex\DhlParcelDeShippingV2\Model\VAS;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASCashOnDelivery;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASDhlRetoure;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASEndorsement;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASIdentCheck;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASIdentCheckMinimumAge;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASVisualCheckOfAge;
use Prefabcortex\DhlParcelDeShippingV2\Model\Weight;
use Prefabcortex\DhlParcelDeShippingV2\Model\WeightUom;

/**
 * One schema-conformant instance of every model in this package.
 *
 * Values are the ones the API description states — `example` or `default` where it gives one, a
 * typed placeholder where it does not. They are shaped like real data, not equal to it: nothing
 * here has been sent to the service, so a value being accepted by the schema says nothing about it
 * being accepted by the server.
 */
final class ModelFixtures
{
    public static function buildServiceInformation(): ServiceInformation
    {
        return ServiceInformation::create();
    }

    public static function buildServiceInformationAmp(): ServiceInformationAmp
    {
        return ServiceInformationAmp::create()
            ->withName('pp-parcel-shipping-native')
            ->withEnv('sandbox')
            ->withVersion('v2.0.4')
            ->withRev('22');
    }

    public static function buildServiceInformationBackend(): ServiceInformationBackend
    {
        return ServiceInformationBackend::create()
            ->withEnv('sandbox')
            ->withVersion('v2.1.0');
    }

    public static function buildDocument(): Document
    {
        return Document::create()
            ->withUrl('www.dhl.de/download/myobscurelink?label.png')
            ->withFileFormat(DocumentFileFormat::PDF)
            ->withPrintFormat(DocumentPrintFormat::_910_300_700);
    }

    public static function buildRequestStatus(): RequestStatus
    {
        return RequestStatus::create(
            'ok',
            200,
        )
            ->withStatus(200)
            ->withDetail('The Webservice call ran successfully.');
    }

    public static function buildLabelDataResponse(): LabelDataResponse
    {
        $sstatus = RequestStatus::create(
            'ok',
            200,
        )
            ->withStatus(200)
            ->withDetail('The Webservice call ran successfully.');
        $responseItem = ResponseItem::create($sstatus);

        return LabelDataResponse::create()
            ->withItems([$responseItem]);
    }

    public static function buildResponseItem(): ResponseItem
    {
        $sstatus = RequestStatus::create(
            'ok',
            200,
        )
            ->withStatus(200)
            ->withDetail('The Webservice call ran successfully.');

        return ResponseItem::create($sstatus);
    }

    public static function buildValidationMessageItem(): ValidationMessageItem
    {
        return ValidationMessageItem::create()
            ->withProperty('dimension.weight')
            ->withValidationMessage('The weight is too high')
            ->withValidationState('Error');
    }

    public static function buildSingleManifestResponse(): SingleManifestResponse
    {
        return SingleManifestResponse::create();
    }

    public static function buildBillingNoToSheetNo(): BillingNoToSheetNo
    {
        return BillingNoToSheetNo::create();
    }

    public static function buildShipmentNoToSheetNo(): ShipmentNoToSheetNo
    {
        return ShipmentNoToSheetNo::create();
    }

    public static function buildMultipleManifestResponse(): MultipleManifestResponse
    {
        return MultipleManifestResponse::create();
    }

    public static function buildShortResponseItem(): ShortResponseItem
    {
        $sstatus = RequestStatus::create(
            'ok',
            200,
        )
            ->withStatus(200)
            ->withDetail('The Webservice call ran successfully.');

        return ShortResponseItem::create($sstatus)
            ->withShipmentNo('340434310428091700');
    }

    public static function buildShipmentManifestingRequest(): ShipmentManifestingRequest
    {
        return ShipmentManifestingRequest::create('REPLACE_ME');
    }

    public static function buildBankAccount(): BankAccount
    {
        return BankAccount::create(
            'John D. Rockefeller',
            'DE02100100100006820101',
        )
            ->withBankName('The Iron Bank, Braavos')
            ->withBic('DEUTDEFFXXX');
    }

    public static function buildCommodity(): Commodity
    {
        $itemValue = Value::create(
            ValueCurrency::AED,
            0.0,
        );
        $itemWeight = Weight::create(
            WeightUom::g,
            500.0,
        );

        return Commodity::create(
            'T-Shirt Boys size 164 yellow',
            1,
            $itemValue,
            $itemWeight,
        )
            ->withHsCode('61099090');
    }

    public static function buildContactAddress(): ContactAddress
    {
        return ContactAddress::create(
            'Blumen Krause',
            'Hauptstrasse',
            'Berlin',
            Country::ABW,
        )
            ->withName2('To the attention of Erna.')
            ->withName3('Backdrawer all the way back.')
            ->withDispatchingInformation('PO Box, bpack 24/7')
            ->withAddressHouse('1a')
            ->withAdditionalAddressInformation1('3. Etage')
            ->withAdditionalAddressInformation2('Apartment 12')
            ->withPostalCode('53113')
            ->withState('NRW')
            ->withContactName('Konrad Kontaktmann')
            ->withPhone('+49 170 1234567')
            ->withEmail('mustermann@example.com');
    }

    public static function buildCustomsDetails(): CustomsDetails
    {
        $postalCharges = Value::create(
            ValueCurrency::AED,
            0.0,
        );
        $itemValue = Value::create(
            ValueCurrency::AED,
            0.0,
        );
        $itemWeight = Weight::create(
            WeightUom::g,
            500.0,
        );
        $commodity = Commodity::create(
            'T-Shirt Boys size 164 yellow',
            1,
            $itemValue,
            $itemWeight,
        )
            ->withHsCode('61099090');

        return CustomsDetails::create(
            CustomsDetailsExportType::OTHER,
            $postalCharges,
            [$commodity],
        )
            ->withExportDescription('Detailed description for OTHER goods.')
            ->withMRN('abcd1234567890')
            ->withShipperCustomsRef('DE73282932000074')
            ->withConsigneeCustomsRef('GB73282932000074');
    }

    public static function buildDimensions(): Dimensions
    {
        return Dimensions::create(
            DimensionsUom::cm,
            10,
            20,
            15,
        );
    }

    public static function buildLocker(): Locker
    {
        return Locker::create(
            'Paula Packstation',
            118,
            'REPLACE_ME',
            'Berlin',
            'REPLACE_ME',
        );
    }

    public static function buildPostOffice(): PostOffice
    {
        return PostOffice::create(
            'Fritz Filialabholer',
            518,
            'Berlin',
            'REPLACE_ME',
        )
            ->withEmail('mustermann@example.com');
    }

    public static function buildPOBox(): POBox
    {
        return POBox::create(
            'Joe Black',
            0,
            'Berlin',
            'REPLACE_ME',
        )
            ->withName2('To the attention of Mr. Black.')
            ->withName3('Backdrawer all the way back.');
    }

    public static function buildShipment(): Shipment
    {
        return Shipment::create()
            ->withBillingNumber('33333333330101 or 333333333362aa');
    }

    public static function buildShipmentDetails(): ShipmentDetails
    {
        $weight = Weight::create(
            WeightUom::g,
            500.0,
        );

        return ShipmentDetails::create($weight);
    }

    public static function buildShipmentOrderRequest(): ShipmentOrderRequest
    {
        $shipment = Shipment::create()
            ->withBillingNumber('33333333330101 or 333333333362aa');

        return ShipmentOrderRequest::create(
            'REPLACE_ME',
            [$shipment],
        );
    }

    public static function buildShipper(): Shipper
    {
        return Shipper::create(
            'Blumen Krause',
            'Hauptstrasse',
            'Berlin',
            Country::ABW,
        )
            ->withName2('To the attention of Erna.')
            ->withName3('Backdrawer all the way back.')
            ->withAddressHouse('1a')
            ->withPostalCode('53113')
            ->withContactName('Konrad Kontaktmann')
            ->withEmail('mustermann@example.com');
    }

    public static function buildShipperReference(): ShipperReference
    {
        return ShipperReference::create('REPLACE_ME');
    }

    public static function buildVAS(): VAS
    {
        return VAS::create()
            ->withPreferredNeighbour('Please ring at Meier next door')
            ->withPreferredLocation('Please leave in carport')
            ->withVisualCheckOfAge(VASVisualCheckOfAge::A18)
            ->withNamedPersonOnly(true)
            ->withSignedForByRecipient(true)
            ->withEndorsement(VASEndorsement::RETURN)
            ->withNoNeighbourDelivery(true)
            ->withBulkyGoods(true)
            ->withIndividualSenderRequirement('ZZ')
            ->withPremium(true)
            ->withClosestDropPoint(true)
            ->withParcelOutletRouting('max.mustermann@example.com')
            ->withGoGreenPlus(true)
            ->withPostalDeliveryDutyPaid(true);
    }

    public static function buildVASCashOnDelivery(): VASCashOnDelivery
    {
        return VASCashOnDelivery::create('REPLACE_ME');
    }

    public static function buildVASDhlRetoure(): VASDhlRetoure
    {
        return VASDhlRetoure::create('REPLACE_ME')
            ->withGoGreenPlus(true);
    }

    public static function buildVASIdentCheck(): VASIdentCheck
    {
        return VASIdentCheck::create(
            'Max',
            'Mustermann',
        )
            ->withMinimumAge(VASIdentCheckMinimumAge::A18);
    }

    public static function buildValue(): Value
    {
        return Value::create(
            ValueCurrency::AED,
            0.0,
        );
    }

    public static function buildWeight(): Weight
    {
        return Weight::create(
            WeightUom::g,
            500.0,
        );
    }

    /**
     * Every model that could be built, keyed by class name so a failure names the model.
     *
     * @return iterable<string, array{SelfNormalizingModel, callable(array<int|string, mixed>): SelfNormalizingModel}>
     */
    public static function roundTrips(): iterable
    {
        yield 'ServiceInformation' => [self::buildServiceInformation(), ServiceInformation::fromArray(...)];
        yield 'ServiceInformationAmp' => [self::buildServiceInformationAmp(), ServiceInformationAmp::fromArray(...)];
        yield 'ServiceInformationBackend' => [
            self::buildServiceInformationBackend(),
            ServiceInformationBackend::fromArray(...),
        ];
        yield 'Document' => [self::buildDocument(), Document::fromArray(...)];
        yield 'RequestStatus' => [self::buildRequestStatus(), RequestStatus::fromArray(...)];
        yield 'LabelDataResponse' => [self::buildLabelDataResponse(), LabelDataResponse::fromArray(...)];
        yield 'ResponseItem' => [self::buildResponseItem(), ResponseItem::fromArray(...)];
        yield 'ValidationMessageItem' => [self::buildValidationMessageItem(), ValidationMessageItem::fromArray(...)];
        yield 'SingleManifestResponse' => [self::buildSingleManifestResponse(), SingleManifestResponse::fromArray(...)];
        yield 'BillingNoToSheetNo' => [self::buildBillingNoToSheetNo(), BillingNoToSheetNo::fromArray(...)];
        yield 'ShipmentNoToSheetNo' => [self::buildShipmentNoToSheetNo(), ShipmentNoToSheetNo::fromArray(...)];
        yield 'MultipleManifestResponse' => [
            self::buildMultipleManifestResponse(),
            MultipleManifestResponse::fromArray(...),
        ];
        yield 'ShortResponseItem' => [self::buildShortResponseItem(), ShortResponseItem::fromArray(...)];
        yield 'ShipmentManifestingRequest' => [
            self::buildShipmentManifestingRequest(),
            ShipmentManifestingRequest::fromArray(...),
        ];
        yield 'BankAccount' => [self::buildBankAccount(), BankAccount::fromArray(...)];
        yield 'Commodity' => [self::buildCommodity(), Commodity::fromArray(...)];
        yield 'ContactAddress' => [self::buildContactAddress(), ContactAddress::fromArray(...)];
        yield 'CustomsDetails' => [self::buildCustomsDetails(), CustomsDetails::fromArray(...)];
        yield 'Dimensions' => [self::buildDimensions(), Dimensions::fromArray(...)];
        yield 'Locker' => [self::buildLocker(), Locker::fromArray(...)];
        yield 'PostOffice' => [self::buildPostOffice(), PostOffice::fromArray(...)];
        yield 'POBox' => [self::buildPOBox(), POBox::fromArray(...)];
        yield 'Shipment' => [self::buildShipment(), Shipment::fromArray(...)];
        yield 'ShipmentDetails' => [self::buildShipmentDetails(), ShipmentDetails::fromArray(...)];
        yield 'ShipmentOrderRequest' => [self::buildShipmentOrderRequest(), ShipmentOrderRequest::fromArray(...)];
        yield 'Shipper' => [self::buildShipper(), Shipper::fromArray(...)];
        yield 'ShipperReference' => [self::buildShipperReference(), ShipperReference::fromArray(...)];
        yield 'VAS' => [self::buildVAS(), VAS::fromArray(...)];
        yield 'VASCashOnDelivery' => [self::buildVASCashOnDelivery(), VASCashOnDelivery::fromArray(...)];
        yield 'VASDhlRetoure' => [self::buildVASDhlRetoure(), VASDhlRetoure::fromArray(...)];
        yield 'VASIdentCheck' => [self::buildVASIdentCheck(), VASIdentCheck::fromArray(...)];
        yield 'Value' => [self::buildValue(), Value::fromArray(...)];
        yield 'Weight' => [self::buildWeight(), Weight::fromArray(...)];
    }

    /**
     * Each model with the wire names its document must carry.
     *
     * @return iterable<string, array{SelfNormalizingModel, callable(array<int|string, mixed>): SelfNormalizingModel, list<string>}>
     */
    public static function documentsMissingARequiredProperty(): iterable
    {
        yield 'RequestStatus' => [self::buildRequestStatus(), RequestStatus::fromArray(...), ['title', 'statusCode']];
        yield 'ResponseItem' => [self::buildResponseItem(), ResponseItem::fromArray(...), ['sstatus']];
        yield 'ShortResponseItem' => [self::buildShortResponseItem(), ShortResponseItem::fromArray(...), ['sstatus']];
        yield 'ShipmentManifestingRequest' => [
            self::buildShipmentManifestingRequest(),
            ShipmentManifestingRequest::fromArray(...),
            ['profile'],
        ];
        yield 'BankAccount' => [self::buildBankAccount(), BankAccount::fromArray(...), ['accountHolder', 'iban']];
        yield 'Commodity' => [
            self::buildCommodity(),
            Commodity::fromArray(...),
            ['itemDescription', 'packagedQuantity', 'itemValue', 'itemWeight'],
        ];
        yield 'ContactAddress' => [
            self::buildContactAddress(),
            ContactAddress::fromArray(...),
            ['name1', 'addressStreet', 'city', 'country'],
        ];
        yield 'CustomsDetails' => [
            self::buildCustomsDetails(),
            CustomsDetails::fromArray(...),
            ['exportType', 'postalCharges', 'items'],
        ];
        yield 'Dimensions' => [
            self::buildDimensions(),
            Dimensions::fromArray(...),
            ['uom', 'height', 'length', 'width'],
        ];
        yield 'Locker' => [
            self::buildLocker(),
            Locker::fromArray(...),
            ['name', 'lockerID', 'postNumber', 'city', 'postalCode'],
        ];
        yield 'PostOffice' => [
            self::buildPostOffice(),
            PostOffice::fromArray(...),
            ['name', 'retailID', 'city', 'postalCode'],
        ];
        yield 'POBox' => [self::buildPOBox(), POBox::fromArray(...), ['name1', 'poBoxID', 'city', 'postalCode']];
        yield 'ShipmentDetails' => [self::buildShipmentDetails(), ShipmentDetails::fromArray(...), ['weight']];
        yield 'ShipmentOrderRequest' => [
            self::buildShipmentOrderRequest(),
            ShipmentOrderRequest::fromArray(...),
            ['profile', 'shipments'],
        ];
        yield 'Shipper' => [
            self::buildShipper(),
            Shipper::fromArray(...),
            ['name1', 'addressStreet', 'city', 'country'],
        ];
        yield 'ShipperReference' => [self::buildShipperReference(), ShipperReference::fromArray(...), ['shipperRef']];
        yield 'VASCashOnDelivery' => [
            self::buildVASCashOnDelivery(),
            VASCashOnDelivery::fromArray(...),
            ['transferNote1'],
        ];
        yield 'VASDhlRetoure' => [self::buildVASDhlRetoure(), VASDhlRetoure::fromArray(...), ['billingNumber']];
        yield 'VASIdentCheck' => [self::buildVASIdentCheck(), VASIdentCheck::fromArray(...), ['firstName', 'lastName']];
        yield 'Value' => [self::buildValue(), Value::fromArray(...), ['currency', 'value']];
        yield 'Weight' => [self::buildWeight(), Weight::fromArray(...), ['uom', 'value']];
    }

    /**
     * Each model with, per wire name, a value of a type that property cannot hold.
     *
     * Only properties whose type is a single closed shape appear. A union may legitimately accept
     * what looks like the wrong type, and a schema stating no type accepts anything.
     *
     * @return iterable<string, array{SelfNormalizingModel, callable(array<int|string, mixed>): SelfNormalizingModel, array<string, int|string>}>
     */
    public static function documentsWithAMistypedProperty(): iterable
    {
        yield 'RequestStatus' => [
            self::buildRequestStatus(),
            RequestStatus::fromArray(...),
            ['title' => 42, 'statusCode' => 'not-a-number'],
        ];
        yield 'ResponseItem' => [
            self::buildResponseItem(),
            ResponseItem::fromArray(...),
            ['sstatus' => 'not-an-object'],
        ];
        yield 'ShortResponseItem' => [
            self::buildShortResponseItem(),
            ShortResponseItem::fromArray(...),
            ['sstatus' => 'not-an-object'],
        ];
        yield 'ShipmentManifestingRequest' => [
            self::buildShipmentManifestingRequest(),
            ShipmentManifestingRequest::fromArray(...),
            ['profile' => 42],
        ];
        yield 'BankAccount' => [
            self::buildBankAccount(),
            BankAccount::fromArray(...),
            ['accountHolder' => 42, 'iban' => 42],
        ];
        yield 'Commodity' => [
            self::buildCommodity(),
            Commodity::fromArray(...),
            [
                'itemDescription' => 42,
                'packagedQuantity' => 'not-a-number',
                'itemValue' => 'not-an-object',
                'itemWeight' => 'not-an-object',
            ],
        ];
        yield 'ContactAddress' => [
            self::buildContactAddress(),
            ContactAddress::fromArray(...),
            ['name1' => 42, 'addressStreet' => 42, 'city' => 42, 'country' => 42],
        ];
        yield 'CustomsDetails' => [
            self::buildCustomsDetails(),
            CustomsDetails::fromArray(...),
            ['exportType' => 42, 'postalCharges' => 'not-an-object', 'items' => 'not-an-object'],
        ];
        yield 'Dimensions' => [
            self::buildDimensions(),
            Dimensions::fromArray(...),
            ['uom' => 42, 'height' => 'not-a-number', 'length' => 'not-a-number', 'width' => 'not-a-number'],
        ];
        yield 'Locker' => [
            self::buildLocker(),
            Locker::fromArray(...),
            ['name' => 42, 'lockerID' => 'not-a-number', 'postNumber' => 42, 'city' => 42, 'postalCode' => 42],
        ];
        yield 'PostOffice' => [
            self::buildPostOffice(),
            PostOffice::fromArray(...),
            ['name' => 42, 'retailID' => 'not-a-number', 'city' => 42, 'postalCode' => 42],
        ];
        yield 'POBox' => [
            self::buildPOBox(),
            POBox::fromArray(...),
            ['name1' => 42, 'poBoxID' => 'not-a-number', 'city' => 42, 'postalCode' => 42],
        ];
        yield 'ShipmentDetails' => [
            self::buildShipmentDetails(),
            ShipmentDetails::fromArray(...),
            ['weight' => 'not-an-object'],
        ];
        yield 'ShipmentOrderRequest' => [
            self::buildShipmentOrderRequest(),
            ShipmentOrderRequest::fromArray(...),
            ['profile' => 42, 'shipments' => 'not-an-object'],
        ];
        yield 'Shipper' => [
            self::buildShipper(),
            Shipper::fromArray(...),
            ['name1' => 42, 'addressStreet' => 42, 'city' => 42, 'country' => 42],
        ];
        yield 'ShipperReference' => [
            self::buildShipperReference(),
            ShipperReference::fromArray(...),
            ['shipperRef' => 42],
        ];
        yield 'VASCashOnDelivery' => [
            self::buildVASCashOnDelivery(),
            VASCashOnDelivery::fromArray(...),
            ['transferNote1' => 42],
        ];
        yield 'VASDhlRetoure' => [self::buildVASDhlRetoure(), VASDhlRetoure::fromArray(...), ['billingNumber' => 42]];
        yield 'VASIdentCheck' => [
            self::buildVASIdentCheck(),
            VASIdentCheck::fromArray(...),
            ['firstName' => 42, 'lastName' => 42],
        ];
        yield 'Value' => [self::buildValue(), Value::fromArray(...), ['currency' => 42, 'value' => 'not-a-number']];
        yield 'Weight' => [self::buildWeight(), Weight::fromArray(...), ['uom' => 42, 'value' => 'not-a-number']];
    }
}
