<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\Commodity;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see Commodity
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class CommodityConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'itemDescription' => new Required([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 256), new Type(['string']), new NotNull()]),
                'countryOfOrigin' => new Optional([...CountryConstraint::constraints()]),
                'hsCode' => new Optional([new Length(null, 6), new NotBlank(null, null, null), new Length(null, null, 11), new Type(['string']), new NotNull()]),
                'packagedQuantity' => new Required([new Type(['integer']), new NotNull()]),
                'itemValue' => new Required([new NotNull(), ...ValueConstraint::constraints()]),
                'itemWeight' => new Required([new NotNull(), ...WeightConstraint::constraints()]),
            ], null, null, true),
        ];
    }
}
