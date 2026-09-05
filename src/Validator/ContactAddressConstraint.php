<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\ContactAddress;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see ContactAddress
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class ContactAddressConstraint implements ConstraintProviderInterface
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
                'dispatchingInformation' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 35), new Type(['string']), new NotNull()]),
                'addressStreet' => new Required([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 50), new Type(['string']), new NotNull()]),
                'addressHouse' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 10), new Type(['string']), new NotNull()]),
                'additionalAddressInformation1' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 60), new Type(['string']), new NotNull()]),
                'additionalAddressInformation2' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 60), new Type(['string']), new NotNull()]),
                'postalCode' => new Optional([new Length(null, 3), new NotBlank(null, null, null), new Length(null, null, 10), new Regex('#^[0-9A-Za-z]+([ -]?[0-9A-Za-z]+)*$#'), new Type(['string']), new NotNull()]),
                'city' => new Required([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 40), new Type(['string']), new NotNull()]),
                'state' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 20), new Type(['string']), new NotNull()]),
                'country' => new Required([...CountryConstraint::constraints()]),
                'contactName' => new Optional([new Length(null, 3), new NotBlank(null, null, null), new Length(null, null, 80), new Type(['string']), new NotNull()]),
                'phone' => new Optional([new Length(null, 1), new NotBlank(null, null, null), new Length(null, null, 20), new Type(['string']), new NotNull()]),
                'email' => new Optional([new Length(null, 3), new NotBlank(null, null, null), new Length(null, null, 80), new Type(['string']), new NotNull()]),
            ], null, null, true),
        ];
    }
}
