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
namespace Sylius\Bundle\Theme_Bundle\Dependency_Injection;

use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Source_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Symfony\Component\Config\File_Locator;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Extension\Extension;
use Symfony\Component\Dependency_Injection\Loader\Php_File_Loader;
final class Sylius_Theme_Extension extends Extension
{
    /** @var ConfigurationSourceFactoryInterface[] */
    private array $configuration_source_factories = [];
    /**
     * @internal
     */
    public function load(array $configs, Container_Builder $container): void
    {
        $config = $this->process_configuration($this->get_configuration([], $container), $configs);
        $loader = new Php_File_Loader($container, new File_Locator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
        if ($config['assets']['enabled']) {
            $loader->load('services/integrations/assets.php');
            if ($config['legacy_mode']) {
                $loader->load('services/integrations/legacy_assets.php');
            }
        }
        if ($config['templating']['enabled']) {
            $loader->load('services/integrations/templates.php');
            if ($config['legacy_mode']) {
                $loader->load('services/integrations/legacy_templates.php');
            }
        }
        if ($config['translations']['enabled']) {
            $loader->load('services/integrations/translations.php');
            if ($config['legacy_mode']) {
                $loader->load('services/integrations/legacy_translations.php');
            }
        }
        $this->resolve_configuration_sources($container, $config);
        $container->set_alias(Theme_Context_Interface::class, $config['context']);
        $container->set_alias('sylius.context.theme', Theme_Context_Interface::class)->set_deprecated('sylius/theme-bundle', '2.0', '"%alias_id%" service is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
    }
    public function add_configuration_source_factory(Configuration_Source_Factory_Interface $configuration_source_factory): void
    {
        $this->configuration_source_factories[$configuration_source_factory->get_name()] = $configuration_source_factory;
    }
    public function get_configuration(array $config, Container_Builder $container): Configuration
    {
        $configuration = new Configuration($this->configuration_source_factories);
        $container->add_object_resource($configuration);
        return $configuration;
    }
    private function resolve_configuration_sources(Container_Builder $container, array $config): void
    {
        $configuration_providers = [];
        foreach ($this->configuration_source_factories as $configuration_source_factory) {
            $source_name = $configuration_source_factory->get_name();
            if (isset($config['sources'][$source_name]) && $config['sources'][$source_name]['enabled']) {
                $source_config = $config['sources'][$source_name];
                $configuration_providers[] = $configuration_source_factory->initialize_source($container, $source_config);
            }
        }
        $composite_configuration_provider = $container->get_definition(Configuration_Provider_Interface::class);
        $composite_configuration_provider->replace_argument(0, $configuration_providers);
        foreach ($this->configuration_source_factories as $configuration_source_factory) {
            $container->add_object_resource($configuration_source_factory);
        }
    }
}