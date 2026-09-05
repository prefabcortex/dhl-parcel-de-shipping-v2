<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

/**
 * @internal plumbing of the generated package, not part of its public contract: only the
 *                    generated operations and client touch this, and it may change in any release
 */
trait ValidatorTrait
{
    /**
     * @param array<array-key, mixed> $data
     * @param list<Constraint>        $constraints
     *
     * @throws ValidationException
     */
    final protected function validate(array $data, array $constraints): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $constraints);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }
}
