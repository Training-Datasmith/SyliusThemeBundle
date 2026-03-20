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
namespace Sylius\Bundle\Theme_Bundle\Translation\Provider\Loader;

use Symfony\Component\Translation\Loader\Loader_Interface;
final readonly class Translator_Loader_Provider implements Translator_Loader_Provider_Interface
{
    /**
     * @param LoaderInterface[] $loaders
     */
    public function __construct(private array $loaders = [])
    {
    }
    public function get_loaders(): array
    {
        return $this->loaders;
    }
}