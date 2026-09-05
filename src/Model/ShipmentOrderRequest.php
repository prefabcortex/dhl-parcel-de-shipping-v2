<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;

use function array_is_list;
use function array_key_exists;
use function array_map;
use function array_replace;
use function get_debug_type;
use function is_array;
use function is_string;
use function sprintf;

final readonly class ShipmentOrderRequest implements SelfNormalizingModel
{
    /**
     * @param list<Shipment>           $shipments
     * @param array<int|string, mixed> $additionalProperties
     */
    public function __construct(private string $profile, private array $shipments, private array $additionalProperties)
    {
    }

    /**
     * @param list<Shipment>           $shipments
     * @param array<int|string, mixed> $additionalProperties
     */
    public static function create(string $profile, array $shipments, array $additionalProperties = []): self
    {
        return new self($profile, $shipments, $additionalProperties);
    }

    public function getProfile(): string
    {
        return $this->profile;
    }

    public function withProfile(string $profile): self
    {
        return new self($profile, $this->shipments, $this->additionalProperties);
    }

    /**
     * Shipment array having details for each shipment.
     *
     * @return list<Shipment>
     */
    public function getShipments(): array
    {
        return $this->shipments;
    }

    /** @param list<Shipment> $shipments */
    public function withShipments(array $shipments): self
    {
        return new self($this->profile, $shipments, $this->additionalProperties);
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
        $profile = null;
        $shipments = null;
        if (array_key_exists('profile', $data)) {
            $profileRaw = $data['profile'];
            if (!is_string($profileRaw)) {
                throw new MalformedDataException(sprintf('Property "profile" must be string, got %s.', get_debug_type($profileRaw)));
            }
            $profile = $profileRaw;
            unset($data['profile']);
        }
        if (array_key_exists('shipments', $data)) {
            $shipmentsRaw = $data['shipments'];
            if (!(is_array($shipmentsRaw) && array_is_list($shipmentsRaw))) {
                throw new MalformedDataException(sprintf('Property "shipments" must be array, got %s.', get_debug_type($shipmentsRaw)));
            }
            $shipments = array_map(static function (mixed $value): Shipment {
                if (!is_array($value)) {
                    throw new MalformedDataException(sprintf('Array item must be object, got %s.', get_debug_type($value)));
                }
                /** @var array<string, mixed> $valueTyped */
                $valueTyped = $value;

                return Shipment::fromArray($valueTyped);
            }, $shipmentsRaw);
            unset($data['shipments']);
        }
        $additionalProperties = $data;
        if ($profile === null) {
            throw new MalformedDataException('Required property "profile" is missing from the document.');
        }
        if ($shipments === null) {
            throw new MalformedDataException('Required property "shipments" is missing from the document.');
        }

        return new self($profile, $shipments, $additionalProperties);
    }

    /** @return array<int|string, mixed> */
    #[Override]
    public function toArray(): array
    {
        $dataArray = [];
        $dataArray['profile'] = $this->profile;
        $values = [];
        foreach ($this->shipments as $value) {
            $values[] = $value->toArray();
        }
        $dataArray['shipments'] = $values;
        $dataArray = array_replace($dataArray, $this->getAdditionalProperties());

        return $dataArray;
    }
}
