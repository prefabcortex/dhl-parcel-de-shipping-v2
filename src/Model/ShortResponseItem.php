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

final readonly class ShortResponseItem implements SelfNormalizingModel
{
    /**
     * @param Option<string>           $shipmentNo
     * @param array<int|string, mixed> $additionalProperties
     */
    public function __construct(private RequestStatus $sstatus, private Option $shipmentNo, private array $additionalProperties)
    {
    }

    /** @param array<int|string, mixed> $additionalProperties */
    public static function create(RequestStatus $sstatus, array $additionalProperties = []): self
    {
        return new self($sstatus, None::create(), $additionalProperties);
    }

    /** @return Option<string> */
    public function getShipmentNo(): Option
    {
        return $this->shipmentNo;
    }

    public function withShipmentNo(string $shipmentNo): self
    {
        return new self($this->sstatus, Some::create($shipmentNo), $this->additionalProperties);
    }

    /**
     * General status description for the attached response or response item.
     */
    public function getSstatus(): RequestStatus
    {
        return $this->sstatus;
    }

    public function withSstatus(RequestStatus $sstatus): self
    {
        return new self($sstatus, $this->shipmentNo, $this->additionalProperties);
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
        $sstatus = null;
        if (array_key_exists('shipmentNo', $data)) {
            $shipmentNoRaw = $data['shipmentNo'];
            if (!is_string($shipmentNoRaw)) {
                throw new MalformedDataException(sprintf('Property "shipmentNo" must be string, got %s.', get_debug_type($shipmentNoRaw)));
            }
            $shipmentNo = Some::create($shipmentNoRaw);
            unset($data['shipmentNo']);
        }
        if (array_key_exists('sstatus', $data)) {
            $sstatusRaw = $data['sstatus'];
            if (!is_array($sstatusRaw)) {
                throw new MalformedDataException(sprintf('Property "sstatus" must be object, got %s.', get_debug_type($sstatusRaw)));
            }
            /** @var array<string, mixed> $sstatusRawTyped */
            $sstatusRawTyped = $sstatusRaw;
            $sstatus = RequestStatus::fromArray($sstatusRawTyped);
            unset($data['sstatus']);
        }
        $additionalProperties = $data;
        if ($sstatus === null) {
            throw new MalformedDataException('Required property "sstatus" is missing from the document.');
        }

        return new self($sstatus, $shipmentNo, $additionalProperties);
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
        $dataArray['sstatus'] = $this->sstatus->toArray();
        $dataArray = array_replace($dataArray, $this->getAdditionalProperties());

        return $dataArray;
    }
}
