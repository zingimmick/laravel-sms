<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\Configuration\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/vendor/zing/coding-standard/config/config.php');
    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        Option::SETS,
        [
            SetList::PHP_70,
            SetList::PHP_71,
            SetList::DEAD_CODE,
            SetList::CLEAN_CODE,
            SetList::COMMON,
            SetList::PSR_12,
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
