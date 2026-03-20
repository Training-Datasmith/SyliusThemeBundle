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
namespace Sylius\Bundle\Theme_Bundle\Twig\Loader;

use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Template_Locator_Interface;
use Sylius\Bundle\Theme_Bundle\Twig\Locator\Template_Not_Found_Exception;
use Twig\Loader\Loader_Interface as TwigLoaderInterface;
use Twig\Source;
final readonly class Themed_Template_Loader implements Loader_Interface
{
    public function __construct(private Twig_Loader_Interface $decorated_loader, private Template_Locator_Interface $template_locator, private Theme_Context_Interface $theme_context)
    {
    }
    public function get_source_context(string $name): Source
    {
        try {
            $path = $this->locate_template($name);
            return new Source((string) file_get_contents($path), $name, $path);
        } catch (Template_Not_Found_Exception) {
            return $this->decorated_loader->get_source_context($name);
        }
    }
    public function get_cache_key(string $name): string
    {
        try {
            return $this->locate_template($name);
        } catch (Template_Not_Found_Exception) {
            return $this->decorated_loader->get_cache_key($name);
        }
    }
    /**
     * @param int $time
     */
    public function is_fresh(string $name, $time): bool
    {
        try {
            return filemtime($this->locate_template($name)) <= $time;
        } catch (Template_Not_Found_Exception) {
            return $this->decorated_loader->is_fresh($name, $time);
        }
    }
    public function exists(string $name): bool
    {
        try {
            return stat($this->locate_template($name)) !== false;
        } catch (Template_Not_Found_Exception) {
            return $this->decorated_loader->exists($name);
        }
    }
    /**
     * @throws TemplateNotFoundException
     */
    private function locate_template(string $template): string
    {
        $theme = $this->theme_context->get_theme();
        if ($theme === null) {
            throw new Template_Not_Found_Exception($template, []);
        }
        return $this->template_locator->locate($template, $theme);
    }
}