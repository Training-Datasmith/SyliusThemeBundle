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
final class Circular_Dependency_Checker implements Circular_Dependency_Checker_Interface
{
    public function check(Theme_Interface $theme, array $previous_themes = []): void
    {
        if (0 === count($theme->get_parents())) {
            return;
        }
        $previous_themes[] = $theme;
        foreach ($theme->get_parents() as $parent) {
            if (in_array($parent, $previous_themes, true)) {
                throw new Circular_Dependency_Found_Exception(array_merge($previous_themes, [$parent]));
            }
            $this->check($parent, $previous_themes);
        }
    }
}