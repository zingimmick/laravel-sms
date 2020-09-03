<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ChangelogLinker\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(
        Option::AUTHORS_TO_IGNORE,
        [
            'zingimmick',
            'dependabot[bot]',
            'fossabot',
            'renovate[bot]',
        ]
    );

    $parameters->set(Option::REPOSITORY_URL, 'https://github.com/zingimmick/laravel-sms');
};
