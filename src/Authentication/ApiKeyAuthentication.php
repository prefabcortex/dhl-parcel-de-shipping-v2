<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Authentication;

use InvalidArgumentException;
use Override;
use Psr\Http\Message\RequestInterface;
use SensitiveParameter;

final readonly class ApiKeyAuthentication implements Authenticator
{
    public function __construct(
        #[SensitiveParameter]
        private string $apiKey
    ) {
    }

    /** @throws InvalidArgumentException */
    #[Override]
    public function authenticate(RequestInterface $request): RequestInterface
    {
        $request = $request->withHeader('dhl-api-key', $this->apiKey);

        return $request;
    }

    #[Override]
    public function getSecuritySchemeName(): string
    {
        return 'ApiKey';
    }
}
