<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;
use Rector\Laravel\Set\LaravelSetList;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector;
use Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector;
use Rector\Privatization\Rector\Property\PrivatizeLocalPropertyToPrivatePropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION);
    $containerConfigurator->import(LaravelSetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL);
    $containerConfigurator->import(DoctrineSetList::DOCTRINE_CODE_QUALITY);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::CODE_QUALITY_STRICT);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::PRIVATIZATION);
    $containerConfigurator->import(SetList::NAMING);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_CODE_QUALITY);
    $containerConfigurator->import(SetList::PHP_70);
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);
    $containerConfigurator->import(SetList::EARLY_RETURN);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        Option::SKIP,
        [
            FinalizeClassesWithoutChildrenRector::class,
            ChangeReadOnlyVariableWithDefaultValueToConstantRector::class,
            AddSeeTestAnnotationRector::class,
            RepeatedLiteralToClassConstantRector::class,
            RenameParamToMatchTypeRector::class,
            RenameVariableToMatchMethodCallReturnTypeRector::class,
            EncapsedStringsToSprintfRector::class,
            PrivatizeLocalPropertyToPrivatePropertyRector::class,
            ChangeOrIfReturnToEarlyReturnRector::class,
            PrivatizeLocalGetterToPropertyRector::class,
            ChangeAndIfToEarlyReturnRector::class,
            VarConstantCommentRector::class,
            RemoveUselessParamTagRector::class,
            RemoveUselessReturnTagRector::class,
        ]
    );
    $parameters->set(
        Option::PATHS,
        [
            __DIR__ . '/config',
            __DIR__ . '/src',
            __DIR__ . '/tests',
            __DIR__ . '/changelog-linker.php',
            __DIR__ . '/ecs.php',
            __DIR__ . '/rector.php',
        ]
    );
};
