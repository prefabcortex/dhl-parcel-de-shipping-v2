<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\Shipment;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see Shipment
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class ShipmentConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'product' => new Optional([...ProductConstraint::constraints()]),
                'billingNumber' => new Optional([new Regex('#\w{10}\d{2}\w{2}#'), new Type(['string']), new NotNull()]),
                'refNo' => new Optional([new Length(null, 8), new NotBlank(null, null, null), new Length(null, null, 35), new Type(['string']), new NotNull()]),
                'costCenter' => new Optional([new Length(null, null, 50), new Type(['string']), new NotNull()]),
                'creationSoftware' => new Optional([new Type(['string']), new NotNull()]),
                'shipDate' => new Optional([new Type(['string']), new NotNull()]),
                'shipper' => new Optional([new NotNull()]),
                'consignee' => new Optional([new NotNull()]),
                'details' => new Optional([new NotNull(), ...ShipmentDetailsConstraint::constraints()]),
                'services' => new Optional([new NotNull(), ...VASConstraint::constraints()]),
                'customs' => new Optional([new NotNull(), ...CustomsDetailsConstraint::constraints()]),
            ], null, null, true),
        ];
    }
}
