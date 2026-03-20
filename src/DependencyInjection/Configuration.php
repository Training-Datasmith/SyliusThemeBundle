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

use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Source_Factory_Interface;
use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Config\Definition\Builder\Tree_Builder;
use Symfony\Component\Config\Definition\Configuration_Interface;
final readonly class Configuration implements Configuration_Interface
{
    /**
     * @param ConfigurationSourceFactoryInterface[] $configurationSourceFactories
     */
    public function __construct(private array $configuration_source_factories = [])
    {
    }
    public function get_config_tree_builder(): Tree_Builder
    {
        $tree_builder = new Tree_Builder('sylius_theme');
        /** @var ArrayNodeDefinition $rootNode */
        $root_node = $tree_builder->get_root_node();
        $this->add_sources_configuration($root_node);
        $root_node->children()->array_node('assets')->can_be_disabled();
        $root_node->children()->array_node('templating')->can_be_disabled();
        $root_node->children()->array_node('translations')->can_be_disabled();
        $root_node->children()->scalar_node('context')->default_value('sylius.theme.context.settable')->cannot_be_empty();
        $root_node->children()->boolean_node('legacy_mode')->default_false()->set_deprecated('sylius/theme-bundle', '2.0', '"%node%" at path "%path%" is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.');
        return $tree_builder;
    }
    private function add_sources_configuration(Array_Node_Definition $root_node): void
    {
        $sources_node_builder = $root_node->fix_xml_config('source')->children()->array_node('sources')->children();
        foreach ($this->configuration_source_factories as $source_factory) {
            $source_node = $sources_node_builder->array_node($source_factory->get_name())->can_be_enabled();
            $source_factory->build_configuration($source_node);
        }
    }
}