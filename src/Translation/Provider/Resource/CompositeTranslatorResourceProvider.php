<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ThemeBundle\Translation\Provider\Resource;

final readonly class CompositeTranslatorResourceProvider implements TranslatorResourceProviderInterface
{
    /**
     * @param TranslatorResourceProviderInterface[] $resourceProviders
     */
    public function __construct(private array $resourceProviders = [])
    {
    }

    public function getResources(): array
    {
        $resources = [];

        foreach ($this->resourceProviders as $resourceProvider) {
            $resources = array_merge($resources, $resourceProvider->getResources());
        }

        return $resources;
    }

    public function getResourcesLocales(): array
    {
        $resourcesLocales = [];

        foreach ($this->resourceProviders as $resourceProvider) {
            $resourcesLocales = array_merge($resourcesLocales, $resourceProvider->getResourcesLocales());
        }

        return array_values(array_unique($resourcesLocales));
    }
}
