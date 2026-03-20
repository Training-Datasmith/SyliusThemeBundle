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

use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
final readonly class Hierarchical_Template_Locator implements Template_Locator_Interface
{
    public function __construct(private Template_Locator_Interface $template_locator, private Theme_Hierarchy_Provider_Interface $theme_hierarchy_provider)
    {
    }
    public function locate(string $template, Theme_Interface $theme): string
    {
        $provided_themes = $this->theme_hierarchy_provider->get_theme_hierarchy($theme);
        foreach ($provided_themes as $provided_theme) {
            try {
                return $this->template_locator->locate($template, $provided_theme);
            } catch (Template_Not_Found_Exception) {
                // Ignore if resource cannot be found in given theme.
            }
        }
        throw new Template_Not_Found_Exception($template, $provided_themes);
    }
    public function supports(string $template): bool
    {
        return $this->template_locator->supports($template);
    }
}