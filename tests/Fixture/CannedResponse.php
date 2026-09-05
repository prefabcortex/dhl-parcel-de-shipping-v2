<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

/**
 * The answer {@see RecordingHttpClient} hands back.
 *
 * Deliberately not tailored to any one operation. The smoke test drives the `…Raw()` methods, which
 * return the response untouched, so nothing here has to match a status/content-type row from the
 * description — and pretending it did would be the fabricated half of a mock that proves nothing.
 * What is being watched is the request that went out, not the answer that came back.
 */
final class CannedResponse
{
    /**
     * @throws InvalidArgumentException never, for these constant arguments — 200 is a valid status
     *                                  and the header name is well-formed. The PSR-17 factory says it
     *                                  can, though, and this package declares what it does not catch.
     */
    public static function empty(): ResponseInterface
    {
        $factory = new Psr17Factory();

        return $factory->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($factory->createStream('{}'));
    }
}
