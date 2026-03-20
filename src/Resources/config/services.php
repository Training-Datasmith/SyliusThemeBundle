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

use Sylius\Bundle\Theme_Bundle\Context\Empty_Theme_Context;
use Sylius\Bundle\Theme_Bundle\Context\Settable_Theme_Context;
use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider;
use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\In_Memory_Theme_Repository;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
return static function (Container_Configurator $container): void {
    $container->import('services/*.php');
    $services = $container->services();
    $services->set(Theme_Context_Interface::class, Empty_Theme_Context::class);
    $services->set(Settable_Theme_Context::class)->args([service('sylius.theme.hierarchy_provider')]);
    $services->alias('sylius.theme.context.settable', Settable_Theme_Context::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Theme_Repository_Interface::class, In_Memory_Theme_Repository::class)->args([service('sylius.theme.loader')]);
    $services->alias('sylius.repository.theme', Theme_Repository_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    $services->set(Theme_Hierarchy_Provider_Interface::class, Theme_Hierarchy_Provider::class);
    $services->alias('sylius.theme.hierarchy_provider', Theme_Hierarchy_Provider_Interface::class)->deprecate('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
};