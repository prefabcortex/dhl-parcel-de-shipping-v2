<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

use Override;

use function array_map;
use function array_values;

/**
 * An ordered list of parameter values.
 *
 * Rendered in OpenAPI's default array style (`style: form, explode: true`): the wire name is
 * repeated once per element (`tags=a&tags=b`) rather than PHP's `tags[0]=a` bracket notation,
 * which real APIs reject.
 *
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
final readonly class ListValue implements ParameterValue
{
    /** @param list<ParameterValue> $values */
    public function __construct(public array $values)
    {
    }

    /**
     * The common case: a generated parameter object holds an `array<string>` (or int/float/bool)
     * and needs no per-element wrapping at the call site.
     *
     * Keys are dropped rather than required to be absent: a parameter object is filled through a
     * setter that accepts any `array<string>`, so the caller may well hand over one with gaps —
     * and this style puts only the values on the wire anyway, in order.
     *
     * @param array<array-key, string|int|float|bool> $scalars
     */
    public static function ofScalars(array $scalars): self
    {
        return new self(array_values(array_map(static fn (string|int|float|bool $scalar): ParameterValue => new ScalarValue($scalar), $scalars)));
    }

    #[Override]
    public function toQueryPairs(string $name, bool $allowReserved): array
    {
        $pairs = [];
        foreach ($this->values as $value) {
            foreach ($value->toQueryPairs($name, $allowReserved) as $pair) {
                $pairs[] = $pair;
            }
        }

        return $pairs;
    }

    #[Override]
    public function toHeaderValues(): array
    {
        $headerValues = [];
        foreach ($this->values as $value) {
            foreach ($value->toHeaderValues() as $headerValue) {
                $headerValues[] = $headerValue;
            }
        }

        return $headerValues;
    }
}
