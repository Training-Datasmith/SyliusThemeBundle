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
namespace Sylius\Bundle\Theme_Bundle\Twig\Loader;

use Twig\Loader\Exists_Loader_Interface;
use Twig\Loader\Loader_Interface as TwigLoaderInterface;
if (class_exists(Exists_Loader_Interface::class)) {
    /**
     * Twig 2.x compatibility
     *
     * @internal
     */
    interface Loader_Interface extends Twig_Loader_Interface, Exists_Loader_Interface
    {
    }
} else {
    /**
     * Twig 3.x compatibility
     *
     * @internal
     */
    interface Loader_Interface extends Twig_Loader_Interface
    {
    }
}