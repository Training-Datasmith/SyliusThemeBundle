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
namespace Sylius\Bundle\Theme_Bundle\Asset;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
interface Path_Resolver_Interface
{
    /**
     * Applies theme hashcode to given asset file in order to distinguish it from
     * another same named assets files with another theme or without it.
     */
    public function resolve(string $path, string $base_path, Theme_Interface $theme): string;
}