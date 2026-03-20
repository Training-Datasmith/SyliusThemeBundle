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

use Symfony\Component\Config\Definition\Configuration_Interface;
use Symfony\Component\Config\Definition\Processor;
final readonly class Symfony_Configuration_Processor implements Configuration_Processor_Interface
{
    private Configuration_Interface $configuration;
    private Processor $processor;
    public function __construct(Configuration_Interface $configuration, Processor $processor)
    {
        $this->configuration = $configuration;
        $this->processor = $processor;
    }
    public function process(array $configs): array
    {
        return $this->processor->process_configuration($this->configuration, $configs);
    }
}