<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Authentication;

use InvalidArgumentException;
use Override;
use Psr\Http\Message\RequestInterface;
use SensitiveParameter;

use function base64_encode;
use function sprintf;

final readonly class BasicAuthAuthentication implements Authenticator
{
    public function __construct(
        #[SensitiveParameter]
        private string $username,
        #[SensitiveParameter]
        private string $password
    ) {
    }

    /** @throws InvalidArgumentException */
    #[Override]
    public function authenticate(RequestInterface $request): RequestInterface
    {
        $header = sprintf('Basic %s', base64_encode(sprintf('%s:%s', $this->username, $this->password)));
        $request = $request->withHeader('Authorization', $header);

        return $request;
    }

    #[Override]
    public function getSecuritySchemeName(): string
    {
        return 'BasicAuth';
    }
}
