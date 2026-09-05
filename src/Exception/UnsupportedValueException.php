<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Exception;

use InvalidArgumentException;

/**
 * A value handed to the package that it cannot put on the wire.
 *
 * Three places ask this question and all three ask it before a request exists: the base URI that
 * names no scheme and no host and could therefore address no server, a query parameter holding
 * something with no query-string form, and a multipart field holding something that is not a form
 * part. They differ in when the caller supplied the value, not in what went wrong with it — so
 * they are one type, and a consumer who wants to tell them apart reads the message rather than
 * catching a third class.
 *
 * Extends `InvalidArgumentException` rather than replacing it. That is the type all three sites
 * threw before, the type every published `@throws` named, and the type a consumer's existing
 * `catch` is written against; a subclass keeps all three correct and adds the marker. Note the
 * root is load-bearing and not interchangeable with the one {@see MalformedDataException} uses:
 * `InvalidArgumentException` descends from `LogicException` and `UnexpectedValueException` from
 * `RuntimeException`, so the two live in disjoint halves of the SPL tree and moving a site across
 * would silently stop matching the catch written for it.
 *
 * That split is also the honest line between the two. This one says the caller passed something
 * wrong — knowable before anything is sent, fixable in their code. `MalformedDataException` says a
 * document was not what its description promised, which is only knowable once it arrives.
 *
 * It is an {@see ApiException} and not a `ResponseException` for the same reason
 * {@see NoHttpClientException} is not: nothing has been sent, so there is no response to hand back.
 */
final class UnsupportedValueException extends InvalidArgumentException implements ApiException
{
}
