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

use Sylius\Bundle\Theme_Bundle\Asset\Installer\Assets_Installer;
use Sylius\Bundle\Theme_Bundle\Asset\Installer\Assets_Installer_Interface;
use Sylius\Bundle\Theme_Bundle\Asset\Installer\Assets_Provider;
use Sylius\Bundle\Theme_Bundle\Asset\Installer\Assets_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Asset\Installer\Output_Aware_Assets_Installer;
use Sylius\Bundle\Theme_Bundle\Asset\Package\Path_Package;
use Sylius\Bundle\Theme_Bundle\Asset\Path_Resolver;
use Sylius\Bundle\Theme_Bundle\Asset\Path_Resolver_Interface;
use Sylius\Bundle\Theme_Bundle\Command\Assets_Install_Command;
use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Sylius\Bundle\Theme_Bundle\Filesystem\Filesystem_Interface;
use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Assets_Install_Command::class)->args([service(Assets_Installer_Interface::class), '%kernel.project_dir%'])->tag('console.command');
    $services->set(Assets_Installer_Interface::class, Assets_Installer::class)->args([service('filesystem'), service('kernel'), service(Theme_Repository_Interface::class), service(Path_Resolver_Interface::class), service(Assets_Provider_Interface::class)]);
    $services->set(Output_Aware_Assets_Installer::class)->decorate(Assets_Installer_Interface::class, null, 256)->args([service('.inner')]);
    $services->set(Path_Resolver_Interface::class, Path_Resolver::class)->args([service(Assets_Provider_Interface::class), service(Filesystem_Interface::class)]);
    $services->set(Assets_Provider_Interface::class, Assets_Provider::class)->args([service('kernel'), service(Theme_Hierarchy_Provider_Interface::class)]);
    // Overridden services
    $services->set('assets.path_package', Path_Package::class)->args([abstract_arg('base path'), abstract_arg('version strategy'), service(Theme_Context_Interface::class), service(Path_Resolver_Interface::class), service('assets.context')])->abstract();
};