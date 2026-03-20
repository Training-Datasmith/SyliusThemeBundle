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

use Sylius\Bundle\Theme_Bundle\Factory\Finder_Factory;
use Sylius\Bundle\Theme_Bundle\Factory\Finder_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Filesystem\Filesystem;
use Sylius\Bundle\Theme_Bundle\Filesystem\Filesystem_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Filesystem_Interface::class, Filesystem::class);
    $services->set(Finder_Factory_Interface::class, Finder_Factory::class);
};