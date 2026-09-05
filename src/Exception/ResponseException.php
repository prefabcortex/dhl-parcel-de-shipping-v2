<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * An {@see ApiException} raised by a response that arrived — and therefore one that can hand the
 * response back.
 *
 * Both accessors existed on every thrown class long before this interface did, and on nothing
 * else: 29 final classes in a package the size of the DHL client, each declaring the same two
 * methods, none of them reachable from a `catch` of the type the README told the reader to use.
 * Static analysis called that what it was — "undefined method on `ApiException`" — and the way
 * out was an `instanceof` chain over every concrete class. Declaring the pair here is what makes
 * the documented shape and the usable shape the same shape.
 *
 * It is deliberately *not* declared on `ApiException` itself. Doing that would exclude the two
 * exceptions that have no response to return — `NoHttpClientException`, thrown while the client
 * is still being built, and `ValidationException`, which carries a violation list instead — and
 * excluding them is precisely the hole this interface was introduced to close.
 */
interface ResponseException extends ApiException
{
    /**
     *  The response as the PSR-18 client handed it over: status, headers, stream.
     */
    public function getResponse(): ResponseInterface;

    /**
     * The response body as text, exactly as it arrived.
     *
     * Read once at the point the exception was raised, so it survives the stream having been
     * consumed — which it has, by the parsing attempt that failed.
     */
    public function getRawResponse(): string;
}
