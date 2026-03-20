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
namespace Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource;

final readonly class Composite_Translator_Resource_Provider implements Translator_Resource_Provider_Interface
{
    /**
     * @param TranslatorResourceProviderInterface[] $resourceProviders
     */
    public function __construct(private array $resource_providers = [])
    {
    }
    public function get_resources(): array
    {
        $resources = [];
        foreach ($this->resource_providers as $resource_provider) {
            $resources = array_merge($resources, $resource_provider->get_resources());
        }
        return $resources;
    }
    public function get_resources_locales(): array
    {
        $resources_locales = [];
        foreach ($this->resource_providers as $resource_provider) {
            $resources_locales = array_merge($resources_locales, $resource_provider->get_resources_locales());
        }
        return array_values(array_unique($resources_locales));
    }
}