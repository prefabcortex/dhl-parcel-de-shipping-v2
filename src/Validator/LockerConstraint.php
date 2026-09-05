<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\Locker;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see Locker
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class LockerConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'name' => new Required([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 50), new Type(['string']), new NotNull()]),
                'lockerID' => new Required([new LessThanOrEqual(999.0), new GreaterThanOrEqual(100.0), new Type(['integer']), new NotNull()]),
                'postNumber' => new Required([new Regex('#^[0-9]{6,10}$#'), new Type(['string']), new NotNull()]),
                'city' => new Required([new Length(null, null, 40), new Type(['string']), new NotNull()]),
                'country' => new Optional([...CountryConstraint::constraints()]),
                'postalCode' => new Required([new Length(null, 3), new NotBlank(null, null, null), new Length(null, null, 10), new Regex('#^[0-9A-Za-z]+([ -]?[0-9A-Za-z]+)*$#'), new Type(['string']), new NotNull()]),
            ], null, null, true),
        ];
    }
}
