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

use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Handles templates like "@Acme/template.html.twig".
 *
 * @deprecated Deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.
 */
final readonly class LegacyNamespacedTemplateLocator implements TemplateLocatorInterface
{
    public function __construct(private Filesystem $filesystem)
    {
        @trigger_error(sprintf(
            '"%s" is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.',
            self::class,
        ), \E_USER_DEPRECATED);
    }

    public function locate(string $template, ThemeInterface $theme): string
    {
        $this->assertResourcePathIsValid($template);

        $twigNamespace = substr($template, 1, (int) strpos($template, '/') - 1);
        $resourceName = substr($template, (int) strpos($template, '/') + 1);

        $path = sprintf('%s/%s/views/%s', $theme->getPath(), $this->getBundleOrPluginName($twigNamespace), $resourceName);

        if ($this->filesystem->exists($path)) {
            return $path;
        }

        throw new TemplateNotFoundException($template, [$theme]);
    }

    public function supports(string $template): bool
    {
        return str_starts_with($template, '@') && !str_contains($template, 'Resources/views/');
    }

    private function assertResourcePathIsValid(string $template): void
    {
        if (str_contains($template, '..')) {
            throw new \InvalidArgumentException(sprintf('File name "%s" contains invalid characters (..).', $template));
        }
    }

    private function getBundleOrPluginName(string $twigNamespace): string
    {
        if (str_ends_with($twigNamespace, 'Plugin')) {
            return $twigNamespace;
        }

        return $twigNamespace . 'Bundle';
    }
}
