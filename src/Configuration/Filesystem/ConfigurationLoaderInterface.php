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

interface Configuration_Loader_Interface
{
    /**
     * Loads configuration for given identifier (can be theme name or path to configuration file)
     */
    public function load(string $identifier): array;
}