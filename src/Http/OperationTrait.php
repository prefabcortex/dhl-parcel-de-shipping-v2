<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
trait OperationTrait
{
    abstract protected function transformResponseBody(ResponseInterface $response, ContentType $contentType): mixed;

    // Generated per operation (not implemented here) so its @throws tag can list
    // the exact exceptions that operation's transformResponseBody() throws.
    abstract public function parseResponse(ResponseInterface $response): mixed;
}
