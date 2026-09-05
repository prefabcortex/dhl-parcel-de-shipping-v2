<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

use Psr\Http\Message\StreamInterface;

/**
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
final readonly class Payload
{
    /** @param array<string, list<string>> $headers */
    public function __construct(public array $headers, public string|StreamInterface $content)
    {
    }
}
