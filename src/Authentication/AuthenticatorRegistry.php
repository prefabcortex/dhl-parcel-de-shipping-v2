<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Authentication;

use Psr\Http\Message\RequestInterface;

use function array_diff;
use function array_map;
use function in_array;

/**
 * The authenticators a client was built with, and the rule for choosing among them.
 *
 * An operation states its security requirements as alternatives: a list of scheme-name sets, any
 * one of which is sufficient. This applies the first alternative whose schemes are all covered by
 * a registered authenticator, which is what "OR between entries, AND within one" means in an
 * OpenAPI `security` block. A request whose requirements none of the registered authenticators
 * satisfy goes out unsigned — the server, not the client, decides what that is worth.
 *
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
final readonly class AuthenticatorRegistry
{
    /** @param list<Authenticator> $authenticators */
    public function __construct(private array $authenticators)
    {
    }

    /** @param list<list<string>> $securityRequirements alternatives, each a set of scheme names */
    public function authenticate(RequestInterface $request, array $securityRequirements): RequestInterface
    {
        $registeredSchemes = array_map(static fn (Authenticator $authenticator): string => $authenticator->getSecuritySchemeName(), $this->authenticators);
        foreach ($securityRequirements as $requiredSchemes) {
            if ([] === array_diff($requiredSchemes, $registeredSchemes)) {
                foreach ($this->authenticators as $authenticator) {
                    if (in_array($authenticator->getSecuritySchemeName(), $requiredSchemes, true)) {
                        $request = $authenticator->authenticate($request);
                    }
                }

                return $request;
            }
        }

        return $request;
    }
}
