<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Http;

use Prefabcortex\DhlParcelDeShippingV2\Exception\NoHttpClientException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function array_keys;
use function class_exists;
use function is_a;
use function iterator_to_array;

/**
 * Names the PSR-18 client the package will send its requests through.
 *
 * This is deliberately a lookup by name and not a search. The package requires the virtual
 * `psr/http-client-implementation`, which Composer refuses to resolve unless some installed
 * package claims to provide it — so by the time this class runs, *an* implementation exists and
 * the only open question is which one.
 *
 * That requirement is also why `php-http/discovery` is not used here, despite doing the same
 * job better. Discovery declares `provide: {"psr/http-client-implementation": "*"}`, which
 * satisfies the requirement on paper while installing nothing; the package that really installs
 * an implementation is its Composer *plugin*, and that runs only where the consuming project
 * allowlisted it under `config.allow-plugins` — never under `--no-interaction`, so never in CI.
 * With discovery in the dependency graph the requirement is silently always met and the failure
 * moves to the first request; without it, Composer refuses the install and lists the packages to
 * pick from. Keeping this list is the price of that error message, and it is worth paying.
 *
 * A client outside the list is not a failure of the package: it is handed over directly through
 * `ClientConfig::withHttpClient()`, which is what {@see NoHttpClientException} points at.
 *
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
final class HttpClientResolver
{
    /**
     * The factories are passed to the candidates that cannot build a response on their own; the
     * ones that can take no arguments at all.
     *
     * @throws NoHttpClientException when no known implementation is installed
     */
    public static function resolve(ResponseFactoryInterface $responseFactory, StreamFactoryInterface $streamFactory): ClientInterface
    {
        foreach (self::candidates() as $className => $factoryArguments) {
            // Both halves are load-bearing, and the second is not paranoia about the impossible:
            // `GuzzleHttp\Client` exists in Guzzle 6 as well, where it predates PSR-18 and does
            // not implement it. Such a project satisfies the composer requirement through
            // `php-http/guzzle6-adapter` — a different class, further down this list — so
            // skipping to the next candidate is exactly right, and constructing first only to
            // discard the result would be the wasteful way to find that out.
            if (!class_exists($className) || !is_a($className, ClientInterface::class, true)) {
                continue;
            }

            return self::instantiate($className, $factoryArguments, $responseFactory, $streamFactory);
        }
        throw NoHttpClientException::noneOf(self::names());
    }

    /**
     * Builds one candidate, given how many factories its constructor wants.
     *
     * A method of its own so that what the guards in {@see resolve()} established can be
     * *declared* rather than re-asserted inline: a parameter type holds for the whole body,
     * where a narrowing `@var` in the middle of a loop is honoured by one analyser and skipped
     * by the next.
     *
     * @param class-string<ClientInterface> $className
     */
    private static function instantiate(string $className, int $factoryArguments, ResponseFactoryInterface $responseFactory, StreamFactoryInterface $streamFactory): ClientInterface
    {
        return match ($factoryArguments) {
            2 => new $className($responseFactory, $streamFactory),
            1 => new $className($responseFactory),
            default => new $className(),
        };
    }

    /**
     * Known PSR-18 implementations, most common first, each mapped to the number of factory
     * arguments its constructor takes.
     *
     * A generator rather than a `const array`, and that is load-bearing rather than a matter of
     * taste. Out of a constant these names stay *literals* all the way to their use, and a
     * static analyser then resolves `class_exists()` against its own autoloader and folds the
     * loop down to whichever single client the analysed project happens to have installed —
     * reporting "match arm always false" and "instanceof always true" in one consumer's project
     * and nothing in another's. Behind a declared `iterable<string, int>` the names are what
     * they actually are here: data, not references to classes this package depends on.
     *
     * @return iterable<string, int>
     */
    private static function candidates(): iterable
    {
        yield 'Symfony\Component\HttpClient\Psr18Client' => 0;
        yield 'GuzzleHttp\Client' => 0;
        yield 'Http\Adapter\Guzzle7\Client' => 0;
        yield 'Http\Client\Curl\Client' => 2;
        yield 'Buzz\Client\FileGetContents' => 1;
    }

    /**
     * The candidate class names on their own, for the failure message.
     *
     * @return list<string>
     */
    private static function names(): array
    {
        return array_keys(iterator_to_array(self::candidates()));
    }
}
