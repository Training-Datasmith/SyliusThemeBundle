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

use Sylius\Bundle\Theme_Bundle\Twig\Locator\Legacy_Application_Template_Locator;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Legacy_Namespaced_Template_Locator;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Legacy_Application_Template_Locator::class)->args([service('filesystem')])->tag('sylius_theme.twig.template_locator', ['priority' => -64]);
    $services->set(Legacy_Namespaced_Template_Locator::class)->args([service('filesystem')])->tag('sylius_theme.twig.template_locator', ['priority' => -64]);
};