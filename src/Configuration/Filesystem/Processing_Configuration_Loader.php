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
final readonly class Processing_Configuration_Loader implements Configuration_Loader_Interface
{
    public function __construct(private Configuration_Loader_Interface $decorated_loader, private Configuration_Processor_Interface $configuration_processor)
    {
    }
    public function load(string $identifier): array
    {
        $raw_configuration = $this->decorated_loader->load($identifier);
        $configurations = [$raw_configuration];
        if (isset($raw_configuration['extra']['sylius-theme'])) {
            $configurations[] = $raw_configuration['extra']['sylius-theme'];
        }
        return $this->configuration_processor->process($configurations);
    }
}