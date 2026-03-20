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
namespace Sylius\Bundle\Theme_Bundle\Configuration;

use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Definition;
use Symfony\Component\Dependency_Injection\Reference;
interface Configuration_Source_Factory_Interface
{
    public function build_configuration(Array_Node_Definition $node): void;
    /**
     * @see ConfigurationProviderInterface
     *
     * @return Reference|Definition Configuration provider service
     */
    public function initialize_source(Container_Builder $container, array $config);
    public function get_name(): string;
}