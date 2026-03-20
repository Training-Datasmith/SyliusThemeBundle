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

use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Twig\Loader\Themed_Template_Loader;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Application_Template_Locator;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Composite_Template_Locator;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Hierarchical_Template_Locator;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Namespaced_Template_Locator;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Template_Locator_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Application_Template_Locator::class)->args([service('filesystem')])->tag('sylius_theme.twig.template_locator');
    $services->set(Namespaced_Template_Locator::class)->args([service('filesystem')])->tag('sylius_theme.twig.template_locator');
    $services->set(Template_Locator_Interface::class, Composite_Template_Locator::class)->args([tagged_iterator('sylius_theme.twig.template_locator')]);
    $services->set(Hierarchical_Template_Locator::class)->decorate(Template_Locator_Interface::class)->args([service('.inner'), service(Theme_Hierarchy_Provider_Interface::class)]);
    $services->set(Themed_Template_Loader::class)->decorate('twig.loader', null, 256)->args([service('.inner'), service(Template_Locator_Interface::class), service(Theme_Context_Interface::class)]);
};