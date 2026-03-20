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

use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Author_Factory;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Author_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Factory;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Screenshot_Factory;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Screenshot_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Loader\Circular_Dependency_Checker;
use Sylius\Bundle\Theme_Bundle\Loader\Circular_Dependency_Checker_Interface;
use Sylius\Bundle\Theme_Bundle\Loader\Theme_Loader;
use Sylius\Bundle\Theme_Bundle\Loader\Theme_Loader_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Theme_Factory_Interface::class, Theme_Factory::class);
    $services->alias('sylius.factory.theme', Theme_Factory_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Theme_Author_Factory_Interface::class, Theme_Author_Factory::class);
    $services->alias('sylius.factory.theme_author', Theme_Author_Factory_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Theme_Screenshot_Factory_Interface::class, Theme_Screenshot_Factory::class);
    $services->alias('sylius.factory.theme_screenshot', Theme_Screenshot_Factory_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Circular_Dependency_Checker_Interface::class, Circular_Dependency_Checker::class);
    $services->alias('sylius.theme.circular_dependency_checker', Circular_Dependency_Checker_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Theme_Loader_Interface::class, Theme_Loader::class)->args([service(Configuration_Provider_Interface::class), service(Theme_Factory_Interface::class), service(Theme_Author_Factory_Interface::class), service(Theme_Screenshot_Factory_Interface::class), service(Circular_Dependency_Checker_Interface::class)]);
    $services->alias('sylius.theme.loader', Theme_Loader_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
};