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
namespace Sylius\Bundle\Theme_Bundle\Twig\Locator;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
final class Template_Not_Found_Exception extends \RuntimeException
{
    /**
     * @param ThemeInterface[] $themes
     */
    public function __construct(string $template, array $themes)
    {
        parent::__construct(sprintf('Could not find template "%s" using theme(s) "%s".', $template, implode('", "', array_map(static fn(Theme_Interface $theme): string => $theme->get_name(), $themes))));
    }
}