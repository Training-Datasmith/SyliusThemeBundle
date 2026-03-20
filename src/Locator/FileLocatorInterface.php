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
namespace Sylius\Bundle\Theme_Bundle\Locator;

interface File_Locator_Interface
{
    /**
     * @throws \InvalidArgumentException If name is not valid or file was not found
     */
    public function locate_file_named(string $name): string;
    /**
     * @throws \InvalidArgumentException If name is not valid or files were not found
     */
    public function locate_files_named(string $name): array;
}