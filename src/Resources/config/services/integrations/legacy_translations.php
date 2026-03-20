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

use Sylius\Bundle\Theme_Bundle\Factory\Finder_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Finder\Legacy_Translation_Files_Finder;
use Sylius\Bundle\Theme_Bundle\Translation\Finder\Translation_Files_Finder_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Legacy_Translation_Files_Finder::class)->decorate(Translation_Files_Finder_Interface::class, null, 128)->args([service(Finder_Factory_Interface::class)]);
};