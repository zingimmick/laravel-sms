<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitInternalClassFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/vendor/zing/coding-standard/config/config.php');
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [SetList::PSR_12, SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE]);
    $parameters->set(Option::SKIP, [
        YodaStyleFixer::class => null,
        PhpUnitInternalClassFixer::class,
        PhpUnitTestClassRequiresCoversFixer::class,
        NoSuperfluousPhpdocTagsFixer::class,
    ]);
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
