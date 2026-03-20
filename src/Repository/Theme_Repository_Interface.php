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
namespace Sylius\Bundle\Theme_Bundle\Repository;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
interface Theme_Repository_Interface
{
    /**
     * @return array|ThemeInterface[]
     */
    public function find_all(): array;
    public function find_one_by_name(string $name): ?Theme_Interface;
    public function find_one_by_title(string $title): ?Theme_Interface;
}