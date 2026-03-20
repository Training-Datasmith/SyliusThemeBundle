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

use Sylius\Bundle\Theme_Bundle\Asset\Installer\Assets_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Asset\Installer\Legacy_Assets_Provider;
use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Legacy_Assets_Provider::class)->decorate(Assets_Provider_Interface::class)->args([service('.inner'), service('kernel'), service(Theme_Hierarchy_Provider_Interface::class)]);
};