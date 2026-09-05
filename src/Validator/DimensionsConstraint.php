<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\Dimensions;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see Dimensions
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class DimensionsConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'uom' => new Required([...DimensionsUomConstraint::constraints()]),
                'height' => new Required([new Type(['integer']), new NotNull()]),
                'length' => new Required([new Type(['integer']), new NotNull()]),
                'width' => new Required([new Type(['integer']), new NotNull()]),
            ], null, null, true),
        ];
    }
}
