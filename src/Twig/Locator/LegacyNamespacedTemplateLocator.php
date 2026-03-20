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
use Symfony\Component\Filesystem\Filesystem;
/**
 * Handles templates like "@Acme/template.html.twig".
 *
 * @deprecated Deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.
 */
final readonly class Legacy_Namespaced_Template_Locator implements Template_Locator_Interface
{
    public function __construct(private Filesystem $filesystem)
    {
        @trigger_error(sprintf('"%s" is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.', self::class), \E_USER_DEPRECATED);
    }
    public function locate(string $template, Theme_Interface $theme): string
    {
        $this->assert_resource_path_is_valid($template);
        $twig_namespace = substr($template, 1, (int) strpos($template, '/') - 1);
        $resource_name = substr($template, (int) strpos($template, '/') + 1);
        $path = sprintf('%s/%s/views/%s', $theme->get_path(), $this->get_bundle_or_plugin_name($twig_namespace), $resource_name);
        if ($this->filesystem->exists($path)) {
            return $path;
        }
        throw new Template_Not_Found_Exception($template, [$theme]);
    }
    public function supports(string $template): bool
    {
        return str_starts_with($template, '@') && !str_contains($template, 'Resources/views/');
    }
    private function assert_resource_path_is_valid(string $template): void
    {
        if (str_contains($template, '..')) {
            throw new \InvalidArgumentException(sprintf('File name "%s" contains invalid characters (..).', $template));
        }
    }
    private function get_bundle_or_plugin_name(string $twig_namespace): string
    {
        if (str_ends_with($twig_namespace, 'Plugin')) {
            return $twig_namespace;
        }
        return $twig_namespace . 'Bundle';
    }
}