<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture;

use Prefabcortex\DhlParcelDeShippingV2\Model\BankAccount;
use Prefabcortex\DhlParcelDeShippingV2\Model\BillingNoToSheetNo;
use Prefabcortex\DhlParcelDeShippingV2\Model\Commodity;
use Prefabcortex\DhlParcelDeShippingV2\Model\ContactAddress;
use Prefabcortex\DhlParcelDeShippingV2\Model\CustomsDetails;
use Prefabcortex\DhlParcelDeShippingV2\Model\Dimensions;
use Prefabcortex\DhlParcelDeShippingV2\Model\Document;
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
use Prefabcortex\DhlParcelDeShippingV2\Model\VAS;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASCashOnDelivery;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASDhlRetoure;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASIdentCheck;
use Prefabcortex\DhlParcelDeShippingV2\Model\Weight;
use Prefabcortex\DhlParcelDeShippingV2\Validator\BankAccountConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\BillingNoToSheetNoConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\CommodityConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ContactAddressConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\CustomsDetailsConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\DimensionsConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\DocumentConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\LabelDataResponseConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\LockerConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\MultipleManifestResponseConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\POBoxConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\PostOfficeConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\RequestStatusConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ResponseItemConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ServiceInformationAmpConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ServiceInformationBackendConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ServiceInformationConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipmentConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipmentDetailsConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipmentManifestingRequestConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipmentNoToSheetNoConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipmentOrderRequestConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipperConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShipperReferenceConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ShortResponseItemConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\SingleManifestResponseConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ValidationMessageItemConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\ValueConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\VASCashOnDeliveryConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\VASConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\VASDhlRetoureConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\VASIdentCheckConstraint;
use Prefabcortex\DhlParcelDeShippingV2\Validator\WeightConstraint;
use Symfony\Component\Validator\Constraint;

/**
 * The data providers over ModelFixtures: one schema-conformant instance of every model in this
 * package, and what each is checked against.
 *
 * Values are the ones the API description states — `example` or `default` where it gives one, a
 * typed placeholder where it does not. They are shaped like real data, not equal to it: nothing
 * here has been sent to the service, so a value being accepted by the schema says nothing about it
 * being accepted by the server.
 */
final class ModelFixtureProviders
{
    /**
     * Every model that could be built and reads back what it writes, keyed by class name so a
     * failure names the model.
     *
     * @return iterable<string, array{SelfNormalizingModel, callable(array<int|string, mixed>): SelfNormalizingModel}>
     */
    public static function roundTrips(): iterable
    {
        yield 'ServiceInformation' => [ModelFixtures::buildServiceInformation(), ServiceInformation::fromArray(...)];
        yield 'ServiceInformationAmp' => [
            ModelFixtures::buildServiceInformationAmp(),
            ServiceInformationAmp::fromArray(...),
        ];
        yield 'ServiceInformationBackend' => [
            ModelFixtures::buildServiceInformationBackend(),
            ServiceInformationBackend::fromArray(...),
        ];
        yield 'Document' => [ModelFixtures::buildDocument(), Document::fromArray(...)];
        yield 'RequestStatus' => [ModelFixtures::buildRequestStatus(), RequestStatus::fromArray(...)];
        yield 'LabelDataResponse' => [ModelFixtures::buildLabelDataResponse(), LabelDataResponse::fromArray(...)];
        yield 'ResponseItem' => [ModelFixtures::buildResponseItem(), ResponseItem::fromArray(...)];
        yield 'ValidationMessageItem' => [
            ModelFixtures::buildValidationMessageItem(),
            ValidationMessageItem::fromArray(...),
        ];
        yield 'SingleManifestResponse' => [
            ModelFixtures::buildSingleManifestResponse(),
            SingleManifestResponse::fromArray(...),
        ];
        yield 'BillingNoToSheetNo' => [ModelFixtures::buildBillingNoToSheetNo(), BillingNoToSheetNo::fromArray(...)];
        yield 'ShipmentNoToSheetNo' => [ModelFixtures::buildShipmentNoToSheetNo(), ShipmentNoToSheetNo::fromArray(...)];
        yield 'MultipleManifestResponse' => [
            ModelFixtures::buildMultipleManifestResponse(),
            MultipleManifestResponse::fromArray(...),
        ];
        yield 'ShortResponseItem' => [ModelFixtures::buildShortResponseItem(), ShortResponseItem::fromArray(...)];
        yield 'ShipmentManifestingRequest' => [
            ModelFixtures::buildShipmentManifestingRequest(),
            ShipmentManifestingRequest::fromArray(...),
        ];
        yield 'BankAccount' => [ModelFixtures::buildBankAccount(), BankAccount::fromArray(...)];
        yield 'Commodity' => [ModelFixtures::buildCommodity(), Commodity::fromArray(...)];
        yield 'ContactAddress' => [ModelFixtures::buildContactAddress(), ContactAddress::fromArray(...)];
        yield 'CustomsDetails' => [ModelFixtures::buildCustomsDetails(), CustomsDetails::fromArray(...)];
        yield 'Dimensions' => [ModelFixtures::buildDimensions(), Dimensions::fromArray(...)];
        yield 'Locker' => [ModelFixtures::buildLocker(), Locker::fromArray(...)];
        yield 'PostOffice' => [ModelFixtures::buildPostOffice(), PostOffice::fromArray(...)];
        yield 'POBox' => [ModelFixtures::buildPOBox(), POBox::fromArray(...)];
        yield 'Shipment' => [ModelFixtures::buildShipment(), Shipment::fromArray(...)];
        yield 'ShipmentDetails' => [ModelFixtures::buildShipmentDetails(), ShipmentDetails::fromArray(...)];
        yield 'ShipmentOrderRequest' => [
            ModelFixtures::buildShipmentOrderRequest(),
            ShipmentOrderRequest::fromArray(...),
        ];
        yield 'Shipper' => [ModelFixtures::buildShipper(), Shipper::fromArray(...)];
        yield 'ShipperReference' => [ModelFixtures::buildShipperReference(), ShipperReference::fromArray(...)];
        yield 'VAS' => [ModelFixtures::buildVAS(), VAS::fromArray(...)];
        yield 'VASCashOnDelivery' => [ModelFixtures::buildVASCashOnDelivery(), VASCashOnDelivery::fromArray(...)];
        yield 'VASDhlRetoure' => [ModelFixtures::buildVASDhlRetoure(), VASDhlRetoure::fromArray(...)];
        yield 'VASIdentCheck' => [ModelFixtures::buildVASIdentCheck(), VASIdentCheck::fromArray(...)];
        yield 'Value' => [ModelFixtures::buildValue(), Value::fromArray(...)];
        yield 'Weight' => [ModelFixtures::buildWeight(), Weight::fromArray(...)];
    }

    /**
     * Each model with the wire names its document must carry.
     *
     * @return iterable<string, array{SelfNormalizingModel, callable(array<int|string, mixed>): SelfNormalizingModel, list<string>}>
     */
    public static function documentsMissingARequiredProperty(): iterable
    {
        yield 'RequestStatus' => [
            ModelFixtures::buildRequestStatus(),
            RequestStatus::fromArray(...),
            ['title', 'statusCode'],
        ];
        yield 'ResponseItem' => [ModelFixtures::buildResponseItem(), ResponseItem::fromArray(...), ['sstatus']];
        yield 'ShortResponseItem' => [
            ModelFixtures::buildShortResponseItem(),
            ShortResponseItem::fromArray(...),
            ['sstatus'],
        ];
        yield 'ShipmentManifestingRequest' => [
            ModelFixtures::buildShipmentManifestingRequest(),
            ShipmentManifestingRequest::fromArray(...),
            ['profile'],
        ];
        yield 'BankAccount' => [
            ModelFixtures::buildBankAccount(),
            BankAccount::fromArray(...),
            ['accountHolder', 'iban'],
        ];
        yield 'Commodity' => [
            ModelFixtures::buildCommodity(),
            Commodity::fromArray(...),
            ['itemDescription', 'packagedQuantity', 'itemValue', 'itemWeight'],
        ];
        yield 'ContactAddress' => [
            ModelFixtures::buildContactAddress(),
            ContactAddress::fromArray(...),
            ['name1', 'addressStreet', 'city', 'country'],
        ];
        yield 'CustomsDetails' => [
            ModelFixtures::buildCustomsDetails(),
            CustomsDetails::fromArray(...),
            ['exportType', 'postalCharges', 'items'],
        ];
        yield 'Dimensions' => [
            ModelFixtures::buildDimensions(),
            Dimensions::fromArray(...),
            ['uom', 'height', 'length', 'width'],
        ];
        yield 'Locker' => [
            ModelFixtures::buildLocker(),
            Locker::fromArray(...),
            ['name', 'lockerID', 'postNumber', 'city', 'postalCode'],
        ];
        yield 'PostOffice' => [
            ModelFixtures::buildPostOffice(),
            PostOffice::fromArray(...),
            ['name', 'retailID', 'city', 'postalCode'],
        ];
        yield 'POBox' => [
            ModelFixtures::buildPOBox(),
            POBox::fromArray(...),
            ['name1', 'poBoxID', 'city', 'postalCode'],
        ];
        yield 'ShipmentDetails' => [ModelFixtures::buildShipmentDetails(), ShipmentDetails::fromArray(...), ['weight']];
        yield 'ShipmentOrderRequest' => [
            ModelFixtures::buildShipmentOrderRequest(),
            ShipmentOrderRequest::fromArray(...),
            ['profile', 'shipments'],
        ];
        yield 'Shipper' => [
            ModelFixtures::buildShipper(),
            Shipper::fromArray(...),
            ['name1', 'addressStreet', 'city', 'country'],
        ];
        yield 'ShipperReference' => [
            ModelFixtures::buildShipperReference(),
            ShipperReference::fromArray(...),
            ['shipperRef'],
        ];
        yield 'VASCashOnDelivery' => [
            ModelFixtures::buildVASCashOnDelivery(),
            VASCashOnDelivery::fromArray(...),
            ['transferNote1'],
        ];
        yield 'VASDhlRetoure' => [
            ModelFixtures::buildVASDhlRetoure(),
            VASDhlRetoure::fromArray(...),
            ['billingNumber'],
        ];
        yield 'VASIdentCheck' => [
            ModelFixtures::buildVASIdentCheck(),
            VASIdentCheck::fromArray(...),
            ['firstName', 'lastName'],
        ];
        yield 'Value' => [ModelFixtures::buildValue(), Value::fromArray(...), ['currency', 'value']];
        yield 'Weight' => [ModelFixtures::buildWeight(), Weight::fromArray(...), ['uom', 'value']];
    }

    /**
     * Each model with, per wire name, a value of a type that property cannot hold.
     *
     * Only properties whose type is a single closed shape appear. A union may legitimately accept
     * what looks like the wrong type, and a schema stating no type accepts anything.
     *
     * @return iterable<string, array{SelfNormalizingModel, callable(array<int|string, mixed>): SelfNormalizingModel, array<array-key, int|string>}>
     */
    public static function documentsWithAMistypedProperty(): iterable
    {
        yield 'RequestStatus' => [
            ModelFixtures::buildRequestStatus(),
            RequestStatus::fromArray(...),
            ['title' => 42, 'statusCode' => 'not-a-number'],
        ];
        yield 'ResponseItem' => [
            ModelFixtures::buildResponseItem(),
            ResponseItem::fromArray(...),
            ['sstatus' => 'not-an-object'],
        ];
        yield 'ShortResponseItem' => [
            ModelFixtures::buildShortResponseItem(),
            ShortResponseItem::fromArray(...),
            ['sstatus' => 'not-an-object'],
        ];
        yield 'ShipmentManifestingRequest' => [
            ModelFixtures::buildShipmentManifestingRequest(),
            ShipmentManifestingRequest::fromArray(...),
            ['profile' => 42],
        ];
        yield 'BankAccount' => [
            ModelFixtures::buildBankAccount(),
            BankAccount::fromArray(...),
            ['accountHolder' => 42, 'iban' => 42],
        ];
        yield 'Commodity' => [
            ModelFixtures::buildCommodity(),
            Commodity::fromArray(...),
            [
                'itemDescription' => 42,
                'packagedQuantity' => 'not-a-number',
                'itemValue' => 'not-an-object',
                'itemWeight' => 'not-an-object',
            ],
        ];
        yield 'ContactAddress' => [
            ModelFixtures::buildContactAddress(),
            ContactAddress::fromArray(...),
            ['name1' => 42, 'addressStreet' => 42, 'city' => 42, 'country' => 42],
        ];
        yield 'CustomsDetails' => [
            ModelFixtures::buildCustomsDetails(),
            CustomsDetails::fromArray(...),
            ['exportType' => 42, 'postalCharges' => 'not-an-object', 'items' => 'not-an-object'],
        ];
        yield 'Dimensions' => [
            ModelFixtures::buildDimensions(),
            Dimensions::fromArray(...),
            ['uom' => 42, 'height' => 'not-a-number', 'length' => 'not-a-number', 'width' => 'not-a-number'],
        ];
        yield 'Locker' => [
            ModelFixtures::buildLocker(),
            Locker::fromArray(...),
            ['name' => 42, 'lockerID' => 'not-a-number', 'postNumber' => 42, 'city' => 42, 'postalCode' => 42],
        ];
        yield 'PostOffice' => [
            ModelFixtures::buildPostOffice(),
            PostOffice::fromArray(...),
            ['name' => 42, 'retailID' => 'not-a-number', 'city' => 42, 'postalCode' => 42],
        ];
        yield 'POBox' => [
            ModelFixtures::buildPOBox(),
            POBox::fromArray(...),
            ['name1' => 42, 'poBoxID' => 'not-a-number', 'city' => 42, 'postalCode' => 42],
        ];
        yield 'ShipmentDetails' => [
            ModelFixtures::buildShipmentDetails(),
            ShipmentDetails::fromArray(...),
            ['weight' => 'not-an-object'],
        ];
        yield 'ShipmentOrderRequest' => [
            ModelFixtures::buildShipmentOrderRequest(),
            ShipmentOrderRequest::fromArray(...),
            ['profile' => 42, 'shipments' => 'not-an-object'],
        ];
        yield 'Shipper' => [
            ModelFixtures::buildShipper(),
            Shipper::fromArray(...),
            ['name1' => 42, 'addressStreet' => 42, 'city' => 42, 'country' => 42],
        ];
        yield 'ShipperReference' => [
            ModelFixtures::buildShipperReference(),
            ShipperReference::fromArray(...),
            ['shipperRef' => 42],
        ];
        yield 'VASCashOnDelivery' => [
            ModelFixtures::buildVASCashOnDelivery(),
            VASCashOnDelivery::fromArray(...),
            ['transferNote1' => 42],
        ];
        yield 'VASDhlRetoure' => [
            ModelFixtures::buildVASDhlRetoure(),
            VASDhlRetoure::fromArray(...),
            ['billingNumber' => 42],
        ];
        yield 'VASIdentCheck' => [
            ModelFixtures::buildVASIdentCheck(),
            VASIdentCheck::fromArray(...),
            ['firstName' => 42, 'lastName' => 42],
        ];
        yield 'Value' => [
            ModelFixtures::buildValue(),
            Value::fromArray(...),
            ['currency' => 42, 'value' => 'not-a-number'],
        ];
        yield 'Weight' => [
            ModelFixtures::buildWeight(),
            Weight::fromArray(...),
            ['uom' => 42, 'value' => 'not-a-number'],
        ];
    }

    /**
     * Each model whose values all pass their constraints, with those constraints.
     *
     * @return iterable<string, array{SelfNormalizingModel, list<Constraint>}>
     */
    public static function modelsWithTheirConstraints(): iterable
    {
        yield 'ServiceInformation' => [
            ModelFixtures::buildServiceInformation(),
            ServiceInformationConstraint::constraints(),
        ];
        yield 'ServiceInformationAmp' => [
            ModelFixtures::buildServiceInformationAmp(),
            ServiceInformationAmpConstraint::constraints(),
        ];
        yield 'ServiceInformationBackend' => [
            ModelFixtures::buildServiceInformationBackend(),
            ServiceInformationBackendConstraint::constraints(),
        ];
        yield 'Document' => [ModelFixtures::buildDocument(), DocumentConstraint::constraints()];
        yield 'RequestStatus' => [ModelFixtures::buildRequestStatus(), RequestStatusConstraint::constraints()];
        yield 'LabelDataResponse' => [
            ModelFixtures::buildLabelDataResponse(),
            LabelDataResponseConstraint::constraints(),
        ];
        yield 'ResponseItem' => [ModelFixtures::buildResponseItem(), ResponseItemConstraint::constraints()];
        yield 'ValidationMessageItem' => [
            ModelFixtures::buildValidationMessageItem(),
            ValidationMessageItemConstraint::constraints(),
        ];
        yield 'SingleManifestResponse' => [
            ModelFixtures::buildSingleManifestResponse(),
            SingleManifestResponseConstraint::constraints(),
        ];
        yield 'BillingNoToSheetNo' => [
            ModelFixtures::buildBillingNoToSheetNo(),
            BillingNoToSheetNoConstraint::constraints(),
        ];
        yield 'ShipmentNoToSheetNo' => [
            ModelFixtures::buildShipmentNoToSheetNo(),
            ShipmentNoToSheetNoConstraint::constraints(),
        ];
        yield 'MultipleManifestResponse' => [
            ModelFixtures::buildMultipleManifestResponse(),
            MultipleManifestResponseConstraint::constraints(),
        ];
        yield 'ShortResponseItem' => [
            ModelFixtures::buildShortResponseItem(),
            ShortResponseItemConstraint::constraints(),
        ];
        yield 'ShipmentManifestingRequest' => [
            ModelFixtures::buildShipmentManifestingRequest(),
            ShipmentManifestingRequestConstraint::constraints(),
        ];
        yield 'BankAccount' => [ModelFixtures::buildBankAccount(), BankAccountConstraint::constraints()];
        yield 'Commodity' => [ModelFixtures::buildCommodity(), CommodityConstraint::constraints()];
        yield 'ContactAddress' => [ModelFixtures::buildContactAddress(), ContactAddressConstraint::constraints()];
        yield 'CustomsDetails' => [ModelFixtures::buildCustomsDetails(), CustomsDetailsConstraint::constraints()];
        yield 'Dimensions' => [ModelFixtures::buildDimensions(), DimensionsConstraint::constraints()];
        yield 'Locker' => [ModelFixtures::buildLocker(), LockerConstraint::constraints()];
        yield 'PostOffice' => [ModelFixtures::buildPostOffice(), PostOfficeConstraint::constraints()];
        yield 'POBox' => [ModelFixtures::buildPOBox(), POBoxConstraint::constraints()];
        yield 'Shipment' => [ModelFixtures::buildShipment(), ShipmentConstraint::constraints()];
        yield 'ShipmentDetails' => [ModelFixtures::buildShipmentDetails(), ShipmentDetailsConstraint::constraints()];
        yield 'ShipmentOrderRequest' => [
            ModelFixtures::buildShipmentOrderRequest(),
            ShipmentOrderRequestConstraint::constraints(),
        ];
        yield 'Shipper' => [ModelFixtures::buildShipper(), ShipperConstraint::constraints()];
        yield 'ShipperReference' => [ModelFixtures::buildShipperReference(), ShipperReferenceConstraint::constraints()];
        yield 'VAS' => [ModelFixtures::buildVAS(), VASConstraint::constraints()];
        yield 'VASCashOnDelivery' => [
            ModelFixtures::buildVASCashOnDelivery(),
            VASCashOnDeliveryConstraint::constraints(),
        ];
        yield 'VASDhlRetoure' => [ModelFixtures::buildVASDhlRetoure(), VASDhlRetoureConstraint::constraints()];
        yield 'VASIdentCheck' => [ModelFixtures::buildVASIdentCheck(), VASIdentCheckConstraint::constraints()];
        yield 'Value' => [ModelFixtures::buildValue(), ValueConstraint::constraints()];
        yield 'Weight' => [ModelFixtures::buildWeight(), WeightConstraint::constraints()];
    }
}
