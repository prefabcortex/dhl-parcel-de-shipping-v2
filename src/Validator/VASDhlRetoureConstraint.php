<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\VASDhlRetoure;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see VASDhlRetoure
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class VASDhlRetoureConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'billingNumber' => new Required([new Regex('#\w{10}\d{2}\w{2}#'), new Type(['string']), new NotNull()]),
                'refNo' => new Optional([new Length(null, 6), new NotBlank(null, null, null), new Length(null, null, 50), new Type(['string']), new NotNull()]),
                'returnAddress' => new Optional([new NotNull(), ...ContactAddressConstraint::constraints()]),
                'goGreenPlus' => new Optional([new Type(['bool']), new NotNull()]),
            ], null, null, true),
        ];
    }
}
