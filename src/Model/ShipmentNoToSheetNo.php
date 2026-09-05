<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Optional\None;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Option;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Some;

use function array_key_exists;
use function array_replace;
use function get_debug_type;
use function is_array;
use function is_string;
use function sprintf;

final readonly class ShipmentNoToSheetNo implements SelfNormalizingModel
{
    /**
     * @param Option<string>           $shipmentNo
     * @param Option<string>           $sheetNo
     * @param Option<RequestStatus>    $sstatus
     * @param array<int|string, mixed> $additionalProperties
     */
    public function __construct(private Option $shipmentNo, private Option $sheetNo, private Option $sstatus, private array $additionalProperties)
    {
    }

    /** @param array<int|string, mixed> $additionalProperties */
    public static function create(array $additionalProperties = []): self
    {
        return new self(None::create(), None::create(), None::create(), $additionalProperties);
    }

    /** @return Option<string> */
    public function getShipmentNo(): Option
    {
        return $this->shipmentNo;
    }

    public function withShipmentNo(string $shipmentNo): self
    {
        return new self(Some::create($shipmentNo), $this->sheetNo, $this->sstatus, $this->additionalProperties);
    }

    /** @return Option<string> */
    public function getSheetNo(): Option
    {
        return $this->sheetNo;
    }

    public function withSheetNo(string $sheetNo): self
    {
        return new self($this->shipmentNo, Some::create($sheetNo), $this->sstatus, $this->additionalProperties);
    }

    /**
     * General status description for the attached response or response item.
     *
     * @return Option<RequestStatus>
     */
    public function getSstatus(): Option
    {
        return $this->sstatus;
    }

    public function withSstatus(RequestStatus $sstatus): self
    {
        return new self($this->shipmentNo, $this->sheetNo, Some::create($sstatus), $this->additionalProperties);
    }

    /** @return array<int|string, mixed> */
    public function getAdditionalProperties(): array
    {
        return $this->additionalProperties;
    }

    /**
     * @param array<int|string, mixed> $data
     *
     * @throws MalformedDataException
     */
    public static function fromArray(array $data): self
    {
        $shipmentNo = None::create();
        $sheetNo = None::create();
        $sstatus = None::create();
        if (array_key_exists('shipmentNo', $data)) {
            $shipmentNoRaw = $data['shipmentNo'];
            if (!is_string($shipmentNoRaw)) {
                throw new MalformedDataException(sprintf('Property "shipmentNo" must be string, got %s.', get_debug_type($shipmentNoRaw)));
            }
            $shipmentNo = Some::create($shipmentNoRaw);
            unset($data['shipmentNo']);
        }
        if (array_key_exists('sheetNo', $data)) {
            $sheetNoRaw = $data['sheetNo'];
            if (!is_string($sheetNoRaw)) {
                throw new MalformedDataException(sprintf('Property "sheetNo" must be string, got %s.', get_debug_type($sheetNoRaw)));
            }
            $sheetNo = Some::create($sheetNoRaw);
            unset($data['sheetNo']);
        }
        if (array_key_exists('sstatus', $data)) {
            $sstatusRaw = $data['sstatus'];
            if (!is_array($sstatusRaw)) {
                throw new MalformedDataException(sprintf('Property "sstatus" must be object, got %s.', get_debug_type($sstatusRaw)));
            }
            /** @var array<string, mixed> $sstatusRawTyped */
            $sstatusRawTyped = $sstatusRaw;
            $sstatus = Some::create(RequestStatus::fromArray($sstatusRawTyped));
            unset($data['sstatus']);
        }
        $additionalProperties = $data;

        return new self($shipmentNo, $sheetNo, $sstatus, $additionalProperties);
    }

    /** @return array<int|string, mixed> */
    #[Override]
    public function toArray(): array
    {
        $dataArray = [];
        $shipmentNoOption = $this->shipmentNo;
        if ($shipmentNoOption->isDefined()) {
            $shipmentNo = $shipmentNoOption->get();
            $dataArray['shipmentNo'] = $shipmentNo;
        }
        $sheetNoOption = $this->sheetNo;
        if ($sheetNoOption->isDefined()) {
            $sheetNo = $sheetNoOption->get();
            $dataArray['sheetNo'] = $sheetNo;
        }
        $sstatusOption = $this->sstatus;
        if ($sstatusOption->isDefined()) {
            $sstatus = $sstatusOption->get();
            $dataArray['sstatus'] = $sstatus->toArray();
        }
        $dataArray = array_replace($dataArray, $this->getAdditionalProperties());

        return $dataArray;
    }
}
