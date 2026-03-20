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
use Sylius\Bundle\Theme_Bundle\Factory\Finder_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Finder\Ordering_Translation_Files_Finder;
use Sylius\Bundle\Theme_Bundle\Translation\Finder\Translation_Files_Finder;
use Sylius\Bundle\Theme_Bundle\Translation\Finder\Translation_Files_Finder_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Provider\Loader\Translator_Loader_Provider;
use Sylius\Bundle\Theme_Bundle\Translation\Provider\Loader\Translator_Loader_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource\Composite_Translator_Resource_Provider;
use Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource\Symfony_Translator_Resource_Provider;
use Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource\Theme_Translator_Resource_Provider;
use Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource\Translator_Resource_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Theme_Aware_Translator;
use Sylius\Bundle\Theme_Bundle\Translation\Translator;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Translator::class)->decorate('translator.default', null, 256)->args([service(Translator_Loader_Provider_Interface::class), service(Translator_Resource_Provider_Interface::class), service('translator.formatter'), '%kernel.default_locale%', ['cache_dir' => '%kernel.cache_dir%/translations', 'debug' => '%kernel.debug%']]);
    $services->set(Theme_Aware_Translator::class)->decorate(Translator::class, null, 256)->args([service('.inner'), service(Theme_Context_Interface::class)]);
    $services->set(Translator_Loader_Provider_Interface::class, Translator_Loader_Provider::class)->args([[]]);
    $services->set(Symfony_Translator_Resource_Provider::class)->args([[]]);
    $services->set(Theme_Translator_Resource_Provider::class)->args([service(Translation_Files_Finder_Interface::class), service(Theme_Repository_Interface::class), service(Theme_Hierarchy_Provider_Interface::class)]);
    $services->set(Translator_Resource_Provider_Interface::class, Composite_Translator_Resource_Provider::class)->args([[service(Symfony_Translator_Resource_Provider::class), service(Theme_Translator_Resource_Provider::class)]]);
    $services->set(Translation_Files_Finder_Interface::class, Translation_Files_Finder::class)->args([service(Finder_Factory_Interface::class)]);
    $services->set(Ordering_Translation_Files_Finder::class)->decorate(Translation_Files_Finder_Interface::class, null, -128)->args([service('.inner')]);
};