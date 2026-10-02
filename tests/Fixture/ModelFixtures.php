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

final class ModelFixtures
{
    public static function buildServiceInformation(): ServiceInformation
    {
        return ServiceInformation::builder()->build();
    }

    public static function buildServiceInformationAmp(): ServiceInformationAmp
    {
        return ServiceInformationAmp::builder()
            ->setName('pp-parcel-shipping-native')
            ->setEnv('sandbox')
            ->setVersion('v2.0.4')
            ->setRev('22')
            ->build();
    }

    public static function buildServiceInformationBackend(): ServiceInformationBackend
    {
        return ServiceInformationBackend::builder()
            ->setEnv('sandbox')
            ->setVersion('v2.1.0')
            ->build();
    }

    public static function buildDocument(): Document
    {
        return Document::builder()
            ->setUrl('www.dhl.de/download/myobscurelink?label.png')
            ->setFileFormat(DocumentFileFormat::PDF)
            ->setPrintFormat(DocumentPrintFormat::_910_300_700)
            ->build();
    }

    public static function buildRequestStatus(): RequestStatus
    {
        return RequestStatus::builder(
            // title
            'ok',
            // statusCode
            200,
        )
            ->setStatus(200)
            ->setDetail('The Webservice call ran successfully.')
            ->build();
    }

    public static function buildLabelDataResponse(): LabelDataResponse
    {
        $sstatus = RequestStatus::builder(
            // title
            'ok',
            // statusCode
            200,
        )
            ->setStatus(200)
            ->setDetail('The Webservice call ran successfully.')
            ->build();
        $responseItem = ResponseItem::builder($sstatus)->build();

        return LabelDataResponse::builder()
            ->setItems([$responseItem])
            ->build();
    }

    public static function buildResponseItem(): ResponseItem
    {
        $sstatus = RequestStatus::builder(
            // title
            'ok',
            // statusCode
            200,
        )
            ->setStatus(200)
            ->setDetail('The Webservice call ran successfully.')
            ->build();

        return ResponseItem::builder($sstatus)->build();
    }

    public static function buildValidationMessageItem(): ValidationMessageItem
    {
        return ValidationMessageItem::builder()
            ->setProperty('dimension.weight')
            ->setValidationMessage('The weight is too high')
            ->setValidationState('Error')
            ->build();
    }

    public static function buildSingleManifestResponse(): SingleManifestResponse
    {
        return SingleManifestResponse::builder()->build();
    }

    public static function buildBillingNoToSheetNo(): BillingNoToSheetNo
    {
        return BillingNoToSheetNo::builder()->build();
    }

    public static function buildShipmentNoToSheetNo(): ShipmentNoToSheetNo
    {
        return ShipmentNoToSheetNo::builder()->build();
    }

    public static function buildMultipleManifestResponse(): MultipleManifestResponse
    {
        return MultipleManifestResponse::builder()->build();
    }

    public static function buildShortResponseItem(): ShortResponseItem
    {
        $sstatus = RequestStatus::builder(
            // title
            'ok',
            // statusCode
            200,
        )
            ->setStatus(200)
            ->setDetail('The Webservice call ran successfully.')
            ->build();

        return ShortResponseItem::builder($sstatus)
            ->setShipmentNo('340434310428091700')
            ->build();
    }

    public static function buildShipmentManifestingRequest(): ShipmentManifestingRequest
    {
        return ShipmentManifestingRequest::builder('REPLACE_ME')->build();
    }

    public static function buildBankAccount(): BankAccount
    {
        return BankAccount::builder(
            // accountHolder
            'John D. Rockefeller',
            // iban
            'DE02100100100006820101',
        )
            ->setBankName('The Iron Bank, Braavos')
            ->setBic('DEUTDEFFXXX')
            ->build();
    }

    public static function buildCommodity(): Commodity
    {
        $itemValue = Value::builder(
            // currency
            ValueCurrency::AED,
            // value
            0.0,
        )->build();
        $itemWeight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();

        return Commodity::builder(
            // itemDescription
            'T-Shirt Boys size 164 yellow',
            // packagedQuantity
            1,
            // itemValue
            $itemValue,
            // itemWeight
            $itemWeight,
        )
            ->setHsCode('61099090')
            ->build();
    }

    public static function buildContactAddress(): ContactAddress
    {
        return ContactAddress::builder(
            // name1
            'Blumen Krause',
            // addressStreet
            'Hauptstrasse',
            // city
            'Berlin',
            // country
            Country::ABW,
        )
            ->setName2('To the attention of Erna.')
            ->setName3('Backdrawer all the way back.')
            ->setDispatchingInformation('PO Box, bpack 24/7')
            ->setAddressHouse('1a')
            ->setAdditionalAddressInformation1('3. Etage')
            ->setAdditionalAddressInformation2('Apartment 12')
            ->setPostalCode('53113')
            ->setState('NRW')
            ->setContactName('Konrad Kontaktmann')
            ->setPhone('+49 170 1234567')
            ->setEmail('mustermann@example.com')
            ->build();
    }

    public static function buildCustomsDetails(): CustomsDetails
    {
        $postalCharges = Value::builder(
            // currency
            ValueCurrency::AED,
            // value
            0.0,
        )->build();
        $itemValue = Value::builder(
            // currency
            ValueCurrency::AED,
            // value
            0.0,
        )->build();
        $itemWeight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();
        $commodity = Commodity::builder(
            // itemDescription
            'T-Shirt Boys size 164 yellow',
            // packagedQuantity
            1,
            // itemValue
            $itemValue,
            // itemWeight
            $itemWeight,
        )
            ->setHsCode('61099090')
            ->build();

        return CustomsDetails::builder(
            // exportType
            CustomsDetailsExportType::OTHER,
            // postalCharges
            $postalCharges,
            // items
            [$commodity],
        )
            ->setExportDescription('Detailed description for OTHER goods.')
            ->setMRN('abcd1234567890')
            ->setShipperCustomsRef('DE73282932000074')
            ->setConsigneeCustomsRef('GB73282932000074')
            ->build();
    }

    public static function buildDimensions(): Dimensions
    {
        return Dimensions::builder(
            // uom
            DimensionsUom::cm,
            // height
            10,
            // length
            20,
            // width
            15,
        )->build();
    }

    public static function buildLocker(): Locker
    {
        return Locker::builder(
            // name
            'Paula Packstation',
            // lockerID
            118,
            // postNumber
            '000000',
            // city
            'Berlin',
            // postalCode
            '0 0',
        )->build();
    }

    public static function buildPostOffice(): PostOffice
    {
        return PostOffice::builder(
            // name
            'Fritz Filialabholer',
            // retailID
            518,
            // city
            'Berlin',
            // postalCode
            '0 0',
        )
            ->setEmail('mustermann@example.com')
            ->build();
    }

    public static function buildPOBox(): POBox
    {
        return POBox::builder(
            // name1
            'Joe Black',
            // poBoxID
            0,
            // city
            'Berlin',
            // postalCode
            '0 0',
        )
            ->setName2('To the attention of Mr. Black.')
            ->setName3('Backdrawer all the way back.')
            ->build();
    }

    public static function buildShipment(): Shipment
    {
        return Shipment::builder()
            ->setBillingNumber('33333333330101 or 333333333362aa')
            ->build();
    }

    public static function buildShipmentDetails(): ShipmentDetails
    {
        $weight = Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();

        return ShipmentDetails::builder($weight)->build();
    }

    public static function buildShipmentOrderRequest(): ShipmentOrderRequest
    {
        $shipment = Shipment::builder()
            ->setBillingNumber('33333333330101 or 333333333362aa')
            ->build();

        return ShipmentOrderRequest::builder(
            // profile
            'REPLACE_ME',
            // shipments
            [$shipment],
        )->build();
    }

    public static function buildShipper(): Shipper
    {
        return Shipper::builder(
            // name1
            'Blumen Krause',
            // addressStreet
            'Hauptstrasse',
            // city
            'Berlin',
            // country
            Country::ABW,
        )
            ->setName2('To the attention of Erna.')
            ->setName3('Backdrawer all the way back.')
            ->setAddressHouse('1a')
            ->setPostalCode('53113')
            ->setContactName('Konrad Kontaktmann')
            ->setEmail('mustermann@example.com')
            ->build();
    }

    public static function buildShipperReference(): ShipperReference
    {
        return ShipperReference::builder('REPLACE_ME')->build();
    }

    public static function buildVAS(): VAS
    {
        return VAS::builder()
            ->setPreferredNeighbour('Please ring at Meier next door')
            ->setPreferredLocation('Please leave in carport')
            ->setVisualCheckOfAge(VASVisualCheckOfAge::A18)
            ->setNamedPersonOnly(true)
            ->setSignedForByRecipient(true)
            ->setEndorsement(VASEndorsement::RETURN)
            ->setNoNeighbourDelivery(true)
            ->setBulkyGoods(true)
            ->setIndividualSenderRequirement('ZZ')
            ->setPremium(true)
            ->setClosestDropPoint(true)
            ->setParcelOutletRouting('max.mustermann@example.com')
            ->setGoGreenPlus(true)
            ->setPostalDeliveryDutyPaid(true)
            ->build();
    }

    public static function buildVASCashOnDelivery(): VASCashOnDelivery
    {
        return VASCashOnDelivery::builder('REPLACE_ME')->build();
    }

    public static function buildVASDhlRetoure(): VASDhlRetoure
    {
        return VASDhlRetoure::builder('aaaaaaaaaa00aa')
            ->setGoGreenPlus(true)
            ->build();
    }

    public static function buildVASIdentCheck(): VASIdentCheck
    {
        return VASIdentCheck::builder(
            // firstName
            'Max',
            // lastName
            'Mustermann',
        )
            ->setMinimumAge(VASIdentCheckMinimumAge::A18)
            ->build();
    }

    public static function buildValue(): Value
    {
        return Value::builder(
            // currency
            ValueCurrency::AED,
            // value
            0.0,
        )->build();
    }

    public static function buildWeight(): Weight
    {
        return Weight::builder(
            // uom
            WeightUom::g,
            // value
            500.0,
        )->build();
    }
}
