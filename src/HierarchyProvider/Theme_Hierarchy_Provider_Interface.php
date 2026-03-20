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
namespace Sylius\Bundle\Theme_Bundle\Hierarchy_Provider;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
interface Theme_Hierarchy_Provider_Interface
{
    /**
     * @return ThemeInterface[]
     *
     * @throws \InvalidArgumentException If dependencies could not be resolved.
     */
    public function get_theme_hierarchy(Theme_Interface $theme): array;
}