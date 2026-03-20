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

final readonly class Composite_Configuration_Provider implements Configuration_Provider_Interface
{
    /**
     * @param ConfigurationProviderInterface[] $configurationProviders
     */
    public function __construct(private array $configuration_providers)
    {
    }
    public function get_configurations(): array
    {
        $configurations = [];
        foreach ($this->configuration_providers as $configuration_provider) {
            $configurations = array_merge($configurations, $configuration_provider->get_configurations());
        }
        return $configurations;
    }
}