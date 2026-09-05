<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

/**
 * The value of a single query or header parameter, in the shape OpenAPI allows one to take:
 * a scalar, a list, or a string-keyed map — see {@see ScalarValue}, {@see ListValue} and
 * {@see MapValue}, which are the only implementations.
 *
 * The point of the type is to carry what the generator already knows all the way into the
 * emitted code. A generated parameter object builds these directly from its own typed
 * properties, so serialization never has to ask what a value is: there is no `mixed` to
 * narrow, no `is_scalar()` assertion, and no `match (true)` over `is_int`/`is_bool`/`is_string`.
 * Each shape simply knows how to render itself.
 *
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
interface ParameterValue
{
    /**
     * Renders this value as fully encoded `name=value` query pairs, to be joined with `&`.
     *
     * Returns an empty list when the value contributes nothing (an empty list or map), so that
     * callers never have to filter out blanks — a blank would become a stray `&` and thus an
     * extra, empty parameter on the wire.
     *
     * @param string $name          the parameter's wire name, still unencoded
     * @param bool   $allowReserved whether RFC 3986 reserved characters may travel verbatim
     *
     * @return list<string>
     */
    public function toQueryPairs(string $name, bool $allowReserved): array;

    /**
     * Renders this value as the strings of a single HTTP header, one per repetition.
     *
     * @return list<string>
     */
    public function toHeaderValues(): array;
}
