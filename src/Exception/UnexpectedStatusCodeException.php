<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Exception;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Http\ErrorMessage;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class UnexpectedStatusCodeException extends RuntimeException implements ResponseException
{
    public function __construct(private readonly ResponseInterface $response, private readonly string $rawResponse)
    {
        parent::__construct(ErrorMessage::describeStatus($rawResponse, $response->getStatusCode()), $response->getStatusCode());
    }

    #[Override]
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    #[Override]
    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }
}
