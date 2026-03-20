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

use Sylius\Bundle\Theme_Bundle\Translation\Translator;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
final class Translator_Fallback_Locales_Pass implements Compiler_Pass_Interface
{
    public function process(Container_Builder $container): void
    {
        try {
            $symfony_translator = $container->find_definition('translator.default');
            $sylius_translator = $container->find_definition(Translator::class);
        } catch (\InvalidArgumentException) {
            return;
        }
        $method_calls = array_filter($symfony_translator->get_method_calls(), static fn(array $method_call): bool => 'setFallbackLocales' === $method_call[0]);
        foreach ($method_calls as $method_call) {
            $sylius_translator->add_method_call($method_call[0], $method_call[1]);
        }
    }
}