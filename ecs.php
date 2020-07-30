<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\Configuration\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/vendor/zing/coding-standard/config/config.php');
    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        Option::SETS,
        [
            'psr12',
            'php70',
            'php71',
            'dead-code',
            'clean-code',
            'common',
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
