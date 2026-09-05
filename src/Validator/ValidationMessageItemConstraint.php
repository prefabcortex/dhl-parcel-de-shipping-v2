<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\ValidationMessageItem;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see ValidationMessageItem
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class ValidationMessageItemConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'property' => new Optional([new Type(['string']), new NotNull()]),
                'validationMessage' => new Optional([new Type(['string']), new NotNull()]),
                'validationState' => new Optional([new Type(['string']), new NotNull()]),
            ], null, null, true),
        ];
    }
}
