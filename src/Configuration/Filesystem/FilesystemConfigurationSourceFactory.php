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
namespace Sylius\Bundle\Theme_Bundle\Configuration\Filesystem;

use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Processor_Interface;
use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Source_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Factory\Finder_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Filesystem\Filesystem_Interface;
use Sylius\Bundle\Theme_Bundle\Locator\Recursive_File_Locator;
use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Definition;
use Symfony\Component\Dependency_Injection\Reference;
final class Filesystem_Configuration_Source_Factory implements Configuration_Source_Factory_Interface
{
    public function build_configuration(Array_Node_Definition $node): void
    {
        $filesystem_node = $node->fix_xml_config('directory', 'directories')->children();
        $filesystem_node->scalar_node('filename')->default_value('composer.json')->cannot_be_empty();
        $filesystem_node->scalar_node('scan_depth')->info('Restrict depth to scan for configuration file inside theme folder')->default_value(1);
        $filesystem_node->array_node('directories')->default_value(['%kernel.project_dir%/themes'])->requires_at_least_one_element()->perform_no_deep_merging()->prototype('scalar');
    }
    public function initialize_source(Container_Builder $container, array $config): Definition
    {
        $recursive_file_locator = new Definition(Recursive_File_Locator::class, [new Reference(Finder_Factory_Interface::class), $config['directories'], $config['scan_depth']]);
        $configuration_loader = new Definition(Processing_Configuration_Loader::class, [new Definition(Json_File_Configuration_Loader::class, [new Reference(Filesystem_Interface::class)]), new Reference(Configuration_Processor_Interface::class)]);
        return new Definition(Filesystem_Configuration_Provider::class, [$recursive_file_locator, $configuration_loader, $config['filename']]);
    }
    public function get_name(): string
    {
        return 'filesystem';
    }
}