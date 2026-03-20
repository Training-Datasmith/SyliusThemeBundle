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
final class Theme_Hierarchy_Provider implements Theme_Hierarchy_Provider_Interface
{
    public function get_theme_hierarchy(Theme_Interface $theme): array
    {
        $parents = [];
        foreach ($theme->get_parents() as $parent) {
            $parents = array_merge($parents, $this->get_theme_hierarchy($parent));
        }
        return array_merge([$theme], $parents);
    }
}