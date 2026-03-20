<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Sylius\Bundle\Theme_Bundle\Collector\Theme_Collector;
use Sylius\Bundle\Theme_Bundle\Command\List_Command;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(List_Command::class)->args([service('sylius.repository.theme')])->tag('console.command');
    $services->set(Theme_Collector::class)->args([service('sylius.repository.theme'), service('sylius.context.theme'), service('sylius.theme.hierarchy_provider')])->tag('data_collector', ['template' => '@SyliusTheme/Collector/theme', 'id' => 'sylius_theme']);
};