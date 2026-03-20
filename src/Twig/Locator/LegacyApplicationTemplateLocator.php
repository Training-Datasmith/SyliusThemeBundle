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
 * Handles paths like "template.html.twig" or "Directory/template.html.twig".
 *
 * @deprecated Deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.
 */
final readonly class Legacy_Application_Template_Locator implements Template_Locator_Interface
{
    public function __construct(private Filesystem $filesystem)
    {
        @trigger_error(sprintf('"%s" is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.', self::class), \E_USER_DEPRECATED);
    }
    public function locate(string $template, Theme_Interface $theme): string
    {
        $path = sprintf('%s/views/%s', $theme->get_path(), $template);
        if (!$this->filesystem->exists($path)) {
            throw new Template_Not_Found_Exception($template, [$theme]);
        }
        return $path;
    }
    public function supports(string $template): bool
    {
        return !str_starts_with($template, '@');
    }
}