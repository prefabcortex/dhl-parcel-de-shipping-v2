<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Model\SelfNormalizingModel;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\ModelFixtures;

use function sprintf;

/**
 * Documents that do not fit leave through a documented exception, never a TypeError.
 *
 * Two corruptions of a valid document, both derived from the API description: a required property
 * removed, and a property given a value of a type it cannot hold. Each has to raise
 * `MalformedDataException` — the class the operations already name in their `@throws`, so a caller
 * following the documented contract catches it.
 *
 * The alternative is what makes this worth asserting: an unchecked value reaching a typed
 * constructor raises `TypeError`, which is an `Error` rather than an `Exception` and which no
 * `@throws` here mentions. Both look identical until something runs.
 *
 * What this cannot show is that the service sends what its description promises. These documents
 * were built from that description too.
 */
final class MalformedDataTest extends TestCase
{
    /**
     * @param callable(array<int|string, mixed>): SelfNormalizingModel $fromArray
     * @param list<string>                                             $requiredProperties
     */
    #[DataProviderExternal(ModelFixtures::class, 'documentsMissingARequiredProperty')]
    public function testAMissingRequiredPropertyIsRejected(
        SelfNormalizingModel $model,
        callable $fromArray,
        array $requiredProperties,
    ): void {
        foreach ($requiredProperties as $property) {
            $document = $model->toArray();
            unset($document[$property]);
            try {
                $fromArray($document);
                self::fail(sprintf('a document without the required property "%s" was accepted', $property));
            } catch (MalformedDataException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    /**
     * @param callable(array<int|string, mixed>): SelfNormalizingModel $fromArray
     * @param array<string, int|string>                                $wrongValues
     */
    #[DataProviderExternal(ModelFixtures::class, 'documentsWithAMistypedProperty')]
    public function testAMistypedPropertyIsRejected(
        SelfNormalizingModel $model,
        callable $fromArray,
        array $wrongValues,
    ): void {
        foreach ($wrongValues as $property => $wrongValue) {
            $document = $model->toArray();
            $document[$property] = $wrongValue;
            try {
                $fromArray($document);
                self::fail(sprintf('a value of the wrong type for "%s" was accepted', $property));
            } catch (MalformedDataException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
