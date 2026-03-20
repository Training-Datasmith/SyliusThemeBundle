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
namespace Sylius\Bundle\Theme_Bundle\Translation\Dependency_Injection\Compiler;

use Sylius\Bundle\Theme_Bundle\Translation\Provider\Loader\Translator_Loader_Provider_Interface;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Reference;
final class Translator_Loader_Provider_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        try {
            $loader_provider = $container->find_definition(Translator_Loader_Provider_Interface::class);
        } catch (\InvalidArgumentException) {
            return;
        }
        $tagged_services = $container->find_tagged_service_ids('translation.loader');
        $loaders = [];
        foreach ($tagged_services as $id => $attributes) {
            $loader = $container->find_definition($id);
            $loader->set_lazy(true);
            $loaders[$attributes[0]['alias']] = new Reference($id);
            if (isset($attributes[0]['legacy-alias'])) {
                $loaders[$attributes[0]['legacy-alias']] = new Reference($id);
            }
        }
        $loader_provider->replace_argument(0, $loaders);
    }
}