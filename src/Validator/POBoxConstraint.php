<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\POBox;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see POBox
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class POBoxConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'name1' => new Required([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 50), new Type(['string']), new NotNull()]),
                'name2' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 50), new Type(['string']), new NotNull()]),
                'name3' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 50), new Type(['string']), new NotNull()]),
                'poBoxID' => new Required([new Type(['integer']), new NotNull()]),
                'email' => new Optional([new Length(null, 3), new NotBlank(null, null, null), new Length(null, null, 80), new Type(['string']), new NotNull()]),
                'city' => new Required([new Length(null, null, 80), new Type(['string']), new NotNull()]),
                'country' => new Optional([...CountryConstraint::constraints()]),
                'postalCode' => new Required([new Length(null, 3), new NotBlank(null, null, null), new Length(null, null, 10), new Regex('#^[0-9A-Za-z]+([ -]?[0-9A-Za-z]+)*$#'), new Type(['string']), new NotNull()]),
            ], null, null, true),
        ];
    }
}
