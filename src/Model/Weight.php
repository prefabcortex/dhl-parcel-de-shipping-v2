<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;

use function array_key_exists;
use function array_replace;
use function get_debug_type;
use function is_float;
use function is_int;
use function is_string;
use function sprintf;

final readonly class Weight implements SelfNormalizingModel
{
    /** @param array<int|string, mixed> $additionalProperties */
    public function __construct(private WeightUom $uom, private float $value, private array $additionalProperties)
    {
    }

    /** @param array<int|string, mixed> $additionalProperties */
    public static function create(WeightUom $uom, float $value, array $additionalProperties = []): self
    {
        return new self($uom, $value, $additionalProperties);
    }

    /**
     * metric unit for weight.
     */
    public function getUom(): WeightUom
    {
        return $this->uom;
    }

    public function withUom(WeightUom $uom): self
    {
        return new self($uom, $this->value, $this->additionalProperties);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function withValue(float $value): self
    {
        return new self($this->uom, $value, $this->additionalProperties);
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
        $uom = null;
        $value = null;
        if (array_key_exists('value', $data) && is_int($data['value'])) {
            $data['value'] = (float) $data['value'];
        }
        if (array_key_exists('uom', $data)) {
            $uomRaw = $data['uom'];
            if (!is_string($uomRaw)) {
                throw new MalformedDataException(sprintf('Property "uom" must be string, got %s.', get_debug_type($uomRaw)));
            }
            $uom = WeightUom::tryFrom($uomRaw) ?? throw new MalformedDataException(sprintf('"%s" is not a valid WeightUom.', $uomRaw));
            unset($data['uom']);
        }
        if (array_key_exists('value', $data)) {
            $valueRaw = $data['value'];
            if (!is_float($valueRaw)) {
                throw new MalformedDataException(sprintf('Property "value" must be float, got %s.', get_debug_type($valueRaw)));
            }
            $value = $valueRaw;
            unset($data['value']);
        }
        $additionalProperties = $data;
        if ($uom === null) {
            throw new MalformedDataException('Required property "uom" is missing from the document.');
        }
        if ($value === null) {
            throw new MalformedDataException('Required property "value" is missing from the document.');
        }

        return new self($uom, $value, $additionalProperties);
    }

    /** @return array<int|string, mixed> */
    #[Override]
    public function toArray(): array
    {
        $dataArray = [];
        $dataArray['uom'] = $this->uom->value;
        $dataArray['value'] = $this->value;
        $dataArray = array_replace($dataArray, $this->getAdditionalProperties());

        return $dataArray;
    }
}
