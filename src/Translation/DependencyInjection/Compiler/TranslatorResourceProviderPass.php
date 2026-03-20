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

use Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource\Symfony_Translator_Resource_Provider;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Definition;
use Symfony\Component\Dependency_Injection\Exception\OutOfBoundsException;
final class Translator_Resource_Provider_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        try {
            $symfony_translator = $container->find_definition('translator.default');
            $sylius_resource_provider = $container->find_definition(Symfony_Translator_Resource_Provider::class);
        } catch (\InvalidArgumentException) {
            return;
        }
        $symfony_resources_files = $this->extract_resources_files_from_symfony_translator($symfony_translator);
        $sylius_resource_provider->replace_argument(0, array_merge($sylius_resource_provider->get_argument(0), $symfony_resources_files));
    }
    private function extract_resources_files_from_symfony_translator(Definition $symfony_translator): array
    {
        try {
            $options = $symfony_translator->get_argument(3);
            if (!is_array($options) || !isset($options['resource_files'])) {
                $options = $symfony_translator->get_argument(4);
            }
        } catch (OutOfBoundsException) {
            $options = [];
        }
        $languages_files = isset($options['resource_files']) && is_iterable($options['resource_files']) ? $options['resource_files'] : [];
        $resource_files = [];
        foreach ($languages_files as $files) {
            foreach ($files as $file) {
                $resource_files[] = $file;
            }
        }
        return $resource_files;
    }
}