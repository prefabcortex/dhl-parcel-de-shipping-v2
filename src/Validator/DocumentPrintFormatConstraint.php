<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Validator;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Model\DocumentPrintFormat;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

use function array_map;

/**
 * @see DocumentPrintFormat
 *
 * @internal validation rules for the model above, not part of this package's public
 *                      contract: they may change in any release
 */
final class DocumentPrintFormatConstraint implements ConstraintProviderInterface
{
    #[Override]
    public static function constraints(): array
    {
        return [
            new Type(['string']),
            new Choice(null, array_map(static fn ($c) => $c->value, DocumentPrintFormat::cases())),
            new NotNull(),
        ];
    }
}
