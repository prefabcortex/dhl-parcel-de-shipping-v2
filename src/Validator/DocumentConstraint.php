<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\Document;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @see Document
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class DocumentConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new NotNull(),
            new Collection([
                'b64' => new Optional([new Type(['string']), new NotNull()]),
                'zpl2' => new Optional([new Type(['string']), new NotNull()]),
                'url' => new Optional([new Type(['string']), new NotNull()]),
                'fileFormat' => new Optional([...DocumentFileFormatConstraint::constraints()]),
                'printFormat' => new Optional([...DocumentPrintFormatConstraint::constraints()]),
            ], null, null, true),
        ];
    }
}
