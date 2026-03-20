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
namespace Sylius\Bundle\Theme_Bundle\Translation\Finder;

interface Translation_Files_Finder_Interface
{
    /**
     * @return array Paths to translation files
     */
    public function find_translation_files(string $path): array;
}