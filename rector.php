<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\Function_\RemoveUnusedFunctionRector;
use Rector\PHPStan\Rector\Cast\RecastingRemovalRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\Set\ValueObject\SetList;
use Rector\SOLID\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\SOLID\Rector\Class_\RepeatedLiteralToClassConstantRector;
use Rector\SOLID\Rector\ClassMethod\ChangeReadOnlyVariableWithDefaultValueToConstantRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        Option::SETS,
        [
            SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,
            SetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL,
            SetList::CELEBRITY,
            //        'doctrine',
            SetList::PHPSTAN,
            SetList::PHPUNIT_CODE_QUALITY,
            SetList::SOLID,
            SetList::DOCTRINE_CODE_QUALITY,
            SetList::DEAD_CODE,
            SetList::CODE_QUALITY,
            SetList::PHP_70,
            SetList::PHP_71,
            SetList::PHP_72,
        ]
    );
    $parameters->set(
        Option::EXCLUDE_RECTORS,
        [
            FinalizeClassesWithoutChildrenRector::class,
            ChangeReadOnlyVariableWithDefaultValueToConstantRector::class,
            RecastingRemovalRector::class,
            RepeatedLiteralToClassConstantRector::class,
            AddSeeTestAnnotationRector::class,
            RemoveUnusedFunctionRector::class,
        ]
    );
    $parameters->set(
        Option::PATHS,
        [
            'config',
            'src',
            'tests',
            'ecs.php',
            'rector.php',
        ]
    );
};
