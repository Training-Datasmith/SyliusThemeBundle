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

use Sylius\Bundle\Theme_Bundle\Configuration\Composite_Configuration_Provider;
use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Processor_Interface;
use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Configuration\Symfony_Configuration_Processor;
use Sylius\Bundle\Theme_Bundle\Configuration\Theme_Configuration;
use Symfony\Component\Config\Definition\Processor;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Theme_Configuration::class);
    $services->alias('sylius.theme.configuration', Theme_Configuration::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Configuration_Processor_Interface::class, Symfony_Configuration_Processor::class)->args([service('sylius.theme.configuration'), inline_service(Processor::class)]);
    $services->alias('sylius.theme.configuration.processor', Configuration_Processor_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Configuration_Provider_Interface::class, Composite_Configuration_Provider::class)->args([[]]);
    $services->alias('sylius.theme.configuration.provider', Configuration_Provider_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
};