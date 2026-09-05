<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Exception;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Http\ErrorMessage;
use Prefabcortex\DhlParcelDeShippingV2\Model\LabelDataResponse;
use Psr\Http\Message\ResponseInterface;

final class GetManifestsBadRequestException extends BadRequestException
{
    public function __construct(private readonly LabelDataResponse $labelDataResponse, private readonly ResponseInterface $response, private readonly string $rawResponse)
    {
        parent::__construct(ErrorMessage::describe($rawResponse, 'Bad Request'));
    }

    public function getLabelDataResponse(): LabelDataResponse
    {
        return $this->labelDataResponse;
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
