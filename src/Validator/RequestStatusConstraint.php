<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\RequestStatus;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see RequestStatus
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class RequestStatusConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'title' => new Required([new Type(['string']), new NotNull()]),
                'statusCode' => new Required([new Type(['integer']), new NotNull()]),
                'status' => new Optional([new Type(['integer']), new NotNull()]),
                'instance' => new Optional([new Type(['string']), new NotNull()]),
                'detail' => new Optional([new Length(null, null, 80), new Type(['string']), new NotNull()]),
            ], null, null, true),
        ];
    }
}
