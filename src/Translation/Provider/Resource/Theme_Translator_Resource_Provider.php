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
namespace Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource;

use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Finder\Translation_Files_Finder_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Resource\Theme_Translation_Resource;
use Sylius\Bundle\Theme_Bundle\Translation\Resource\Translation_Resource_Interface;
final readonly class Theme_Translator_Resource_Provider implements Translator_Resource_Provider_Interface
{
    public function __construct(private Translation_Files_Finder_Interface $translation_files_finder, private Theme_Repository_Interface $theme_repository, private Theme_Hierarchy_Provider_Interface $theme_hierarchy_provider)
    {
    }
    public function get_resources(): array
    {
        /** @var ThemeInterface[] $themes */
        $themes = $this->theme_repository->find_all();
        $resources = [];
        foreach ($themes as $theme) {
            $resources = array_merge($resources, $this->extract_resources_from_theme($theme));
        }
        return $resources;
    }
    public function get_resources_locales(): array
    {
        return array_values(array_unique(array_map(static fn(Translation_Resource_Interface $translation_resource): string => $translation_resource->get_locale(), $this->get_resources())));
    }
    private function extract_resources_from_theme(Theme_Interface $main_theme): array
    {
        /** @var ThemeInterface[] $themes */
        $themes = array_reverse($this->theme_hierarchy_provider->get_theme_hierarchy($main_theme));
        $resources = [];
        foreach ($themes as $theme) {
            $paths = $this->translation_files_finder->find_translation_files($theme->get_path());
            foreach ($paths as $path) {
                $resources[] = new Theme_Translation_Resource($main_theme, $path);
            }
        }
        return $resources;
    }
}