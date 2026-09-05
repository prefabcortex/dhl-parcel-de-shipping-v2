<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
interface ConstraintProviderInterface
{
    /** @return list<Constraint> */
    public static function constraints(): array;
}
