<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ThemeBundle\Twig\Locator;

use Sylius\Bundle\ThemeBundle\HierarchyProvider\ThemeHierarchyProviderInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;

final readonly class HierarchicalTemplateLocator implements TemplateLocatorInterface
{
    public function __construct(private TemplateLocatorInterface $templateLocator, private ThemeHierarchyProviderInterface $themeHierarchyProvider)
    {
    }

    public function locate(string $template, ThemeInterface $theme): string
    {
        $providedThemes = $this->themeHierarchyProvider->getThemeHierarchy($theme);
        foreach ($providedThemes as $providedTheme) {
            try {
                return $this->templateLocator->locate($template, $providedTheme);
            } catch (TemplateNotFoundException) {
                // Ignore if resource cannot be found in given theme.
            }
        }

        throw new TemplateNotFoundException($template, $providedThemes);
    }

    public function supports(string $template): bool
    {
        return $this->templateLocator->supports($template);
    }
}
