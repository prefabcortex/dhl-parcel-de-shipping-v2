<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validation;

use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

use function sprintf;

/**
 * A document — a request body on its way out, a response body on its way in — that does not
 * satisfy the constraints its schema declares.
 *
 * Carries {@see ApiException} because it is thrown straight out of `transformResponseBody()`, on
 * the same path and in the same `@throws` list as the operation exceptions beside it. Without the
 * marker the one catch the README documents let it through, which made a failed response check —
 * one of the two most likely ways a call goes wrong — the case a consumer following the
 * documentation was least prepared for.
 *
 * It carries no response accessors and so is not a `ResponseException`: the violation list is the
 * finer answer to the same question, and half of the throw sites are on the request side, where
 * there is no response at all.
 */
final class ValidationException extends RuntimeException implements ApiException
{
    public function __construct(private readonly ConstraintViolationListInterface $violationList)
    {
        parent::__construct(sprintf('Model validation failed with %d errors.', $violationList->count()), 400);
    }

    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
