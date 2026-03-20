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
namespace Sylius\Bundle\Theme_Bundle\Factory;

use Sylius\Bundle\Theme_Bundle\Model\Theme;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
final class Theme_Factory implements Theme_Factory_Interface
{
    public function create(string $name, string $path): Theme_Interface
    {
        return new Theme($name, $path);
    }
}