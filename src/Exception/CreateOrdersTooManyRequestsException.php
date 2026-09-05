<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Exception;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Http\ErrorMessage;
use Prefabcortex\DhlParcelDeShippingV2\Model\RequestStatus;
use Psr\Http\Message\ResponseInterface;

final class CreateOrdersTooManyRequestsException extends TooManyRequestsException
{
    public function __construct(private readonly RequestStatus $requestStatus, private readonly ResponseInterface $response, private readonly string $rawResponse)
    {
        parent::__construct(ErrorMessage::describe($rawResponse, 'Too Many Requests'));
    }

    public function getRequestStatus(): RequestStatus
    {
        return $this->requestStatus;
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
