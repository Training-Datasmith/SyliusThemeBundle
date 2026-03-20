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
namespace Sylius\Bundle\Theme_Bundle\Loader;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
interface Theme_Loader_Interface
{
    /**
     * @return array|ThemeInterface[]
     *
     * @throws ThemeLoadingFailedException
     */
    public function load(): array;
}