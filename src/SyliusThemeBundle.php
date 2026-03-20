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
namespace Sylius\Bundle\Theme_Bundle;

use Sylius\Bundle\Theme_Bundle\Configuration\Filesystem\Filesystem_Configuration_Source_Factory;
use Sylius\Bundle\Theme_Bundle\Configuration\Test\Test_Configuration_Source_Factory;
use Sylius\Bundle\Theme_Bundle\Dependency_Injection\Sylius_Theme_Extension;
use Sylius\Bundle\Theme_Bundle\Translation\Dependency_Injection\Compiler\Translator_Fallback_Locales_Pass;
use Sylius\Bundle\Theme_Bundle\Translation\Dependency_Injection\Compiler\Translator_Loader_Provider_Pass;
use Sylius\Bundle\Theme_Bundle\Translation\Dependency_Injection\Compiler\Translator_Resource_Provider_Pass;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Http_Kernel\Bundle\Bundle;
final class Sylius_Theme_Bundle extends Bundle
{
    public function build(Container_Builder $container): void
    {
        /** @var SyliusThemeExtension $themeExtension */
        $theme_extension = $container->get_extension('sylius_theme');
        $theme_extension->add_configuration_source_factory(new Filesystem_Configuration_Source_Factory());
        $theme_extension->add_configuration_source_factory(new Test_Configuration_Source_Factory());
        $container->add_compiler_pass(new Translator_Fallback_Locales_Pass());
        $container->add_compiler_pass(new Translator_Loader_Provider_Pass());
        $container->add_compiler_pass(new Translator_Resource_Provider_Pass());
    }
}