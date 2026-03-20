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
namespace Sylius\Bundle\Theme_Bundle\Repository;

use Sylius\Bundle\Theme_Bundle\Loader\Theme_Loader_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
final class In_Memory_Theme_Repository implements Theme_Repository_Interface
{
    /** @var ThemeInterface[] */
    private array $themes = [];
    private bool $themes_loaded = false;
    public function __construct(private readonly Theme_Loader_Interface $theme_loader)
    {
    }
    public function find_all(): array
    {
        $this->load_themes_if_needed();
        return $this->themes;
    }
    public function find_one_by_name(string $name): ?Theme_Interface
    {
        $this->load_themes_if_needed();
        return $this->themes[$name] ?? null;
    }
    public function find_one_by_title(string $title): ?Theme_Interface
    {
        $this->load_themes_if_needed();
        foreach ($this->themes as $theme) {
            if ($theme->get_title() === $title) {
                return $theme;
            }
        }
        return null;
    }
    private function load_themes_if_needed(): void
    {
        if ($this->themes_loaded) {
            return;
        }
        $themes = $this->theme_loader->load();
        foreach ($themes as $theme) {
            $this->themes[$theme->get_name()] = $theme;
        }
        $this->themes_loaded = true;
    }
}