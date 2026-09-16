<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Prefabcortex\DhlParcelDeShippingV2\Model\SelfNormalizingModel;
use Prefabcortex\DhlParcelDeShippingV2\Tests\Fixture\ModelFixtures;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

/**
 * Every fixture checked against the rules its model has to satisfy.
 *
 * The fixtures are the values the examples use too, so a model that fails here is one whose example
 * this package would refuse to send. Models whose values could not all be chosen to pass are listed
 * in the docblock of ModelFixtures and not asked.
 */
final class ModelFixturesSatisfyConstraintsTest extends TestCase
{
    /** @param list<Constraint> $constraints */
    #[DataProviderExternal(ModelFixtures::class, 'modelsWithTheirConstraints')]
    public function testFixtureSatisfiesTheConstraintsOfItsModel(SelfNormalizingModel $model, array $constraints): void
    {
        $violations = Validation::createValidator()->validate($model->toArray(), $constraints);
        self::assertCount(0, $violations, (string) $violations);
    }
}
