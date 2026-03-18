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

namespace Sylius\Bundle\ThemeBundle\Twig\Loader;

use Sylius\Bundle\ThemeBundle\Context\ThemeContextInterface;
use Sylius\Bundle\ThemeBundle\Twig\Locator\TemplateLocatorInterface;
use Sylius\Bundle\ThemeBundle\Twig\Locator\TemplateNotFoundException;
use Twig\Loader\LoaderInterface as TwigLoaderInterface;
use Twig\Source;

final readonly class ThemedTemplateLoader implements LoaderInterface
{
    public function __construct(private TwigLoaderInterface $decoratedLoader, private TemplateLocatorInterface $templateLocator, private ThemeContextInterface $themeContext)
    {
    }

    public function getSourceContext(string $name): Source
    {
        try {
            $path = $this->locateTemplate($name);

            return new Source((string) file_get_contents($path), $name, $path);
        } catch (TemplateNotFoundException) {
            return $this->decoratedLoader->getSourceContext($name);
        }
    }

    public function getCacheKey(string $name): string
    {
        try {
            return $this->locateTemplate($name);
        } catch (TemplateNotFoundException) {
            return $this->decoratedLoader->getCacheKey($name);
        }
    }

    /**
     * @param int $time
     */
    public function isFresh(string $name, $time): bool
    {
        try {
            return filemtime($this->locateTemplate($name)) <= $time;
        } catch (TemplateNotFoundException) {
            return $this->decoratedLoader->isFresh($name, $time);
        }
    }

    public function exists(string $name): bool
    {
        try {
            return stat($this->locateTemplate($name)) !== false;
        } catch (TemplateNotFoundException) {
            return $this->decoratedLoader->exists($name);
        }
    }

    /**
     * @throws TemplateNotFoundException
     */
    private function locateTemplate(string $template): string
    {
        $theme = $this->themeContext->getTheme();

        if ($theme === null) {
            throw new TemplateNotFoundException($template, []);
        }

        return $this->templateLocator->locate($template, $theme);
    }
}
