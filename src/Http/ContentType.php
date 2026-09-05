<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

use function explode;
use function strtolower;
use function trim;

/**
 * The media type of a message body, reduced to what identifies it.
 *
 * A `Content-Type` header carries parameters — `application/json; charset=utf-8` — which say
 * nothing about *which* type it is. This drops them once, at the edge, so every comparison
 * afterwards is an exact one against the essence.
 *
 * That exactness is the point. Matching the header as a substring, as this used to, also accepts
 * `application/json-seq` for `application/json`, and any type whose parameter value happens to
 * contain the needle. Carrying the normalized value as a type rather than a bare string keeps a
 * caller from comparing against the raw header by accident.
 *
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
final readonly class ContentType
{
    public const string JSON = 'application/json';
    public const string FORM_URLENCODED = 'application/x-www-form-urlencoded';
    public const string MULTIPART_FORM_DATA = 'multipart/form-data';

    /** @param string $essence lowercased type/subtype, without parameters */
    private function __construct(public string $essence)
    {
    }

    /**
     * Reads a `Content-Type` header value. An absent header — the empty string — yields a content
     * type that matches nothing, which is what "the server did not say" should mean.
     */
    public static function fromHeader(string $header): self
    {
        return new self(strtolower(trim(explode(';', $header, 2)[0])));
    }

    public function is(string $mediaType): bool
    {
        return $this->essence === strtolower($mediaType);
    }

    /**
     * Whether this is any of several media types the operation handles the same way.
     *
     * A status served under `application/json` and `application/problem+json` with one schema used
     * to emit one branch per type, byte for byte identical apart from the string in the condition —
     * four such blocks in a single `transformResponseBody()` of the DHL client, validating the same
     * constraint and building the same model. The duplication is not a matter of taste: a reader
     * has to compare the blocks character by character to establish that they *are* the same, which
     * is exactly the reading no one does.
     *
     * @param list<string> $mediaTypes
     */
    public function isAnyOf(array $mediaTypes): bool
    {
        foreach ($mediaTypes as $mediaType) {
            if ($this->is($mediaType)) {
                return true;
            }
        }

        return false;
    }
}
