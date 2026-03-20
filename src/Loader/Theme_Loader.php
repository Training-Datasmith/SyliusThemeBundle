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
namespace Sylius\Bundle\Theme_Bundle\Loader;

use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Author_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Factory\Theme_Screenshot_Factory_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Author;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Screenshot;
final readonly class Theme_Loader implements Theme_Loader_Interface
{
    public function __construct(private Configuration_Provider_Interface $configuration_provider, private Theme_Factory_Interface $theme_factory, private Theme_Author_Factory_Interface $theme_author_factory, private Theme_Screenshot_Factory_Interface $theme_screenshot_factory, private Circular_Dependency_Checker_Interface $circular_dependency_checker)
    {
    }
    public function load(): array
    {
        $configurations = $this->configuration_provider->get_configurations();
        $themes = $this->hydrate_themes($configurations);
        $this->check_for_circular_dependencies($themes);
        return array_values($themes);
    }
    /**
     * @return ThemeInterface[]
     */
    private function hydrate_themes(array $configurations): array
    {
        $themes = [];
        foreach ($configurations as $configuration) {
            $themes[$configuration['name']] = $this->theme_factory->create($configuration['name'], $configuration['path']);
        }
        foreach ($configurations as $configuration) {
            $theme = $themes[$configuration['name']];
            $theme->set_title($configuration['title'] ?? null);
            $theme->set_description($configuration['description'] ?? null);
            $parent_themes = $this->convert_parents_names_to_parents_objects($configuration['name'], $configuration['parents'], $themes);
            foreach ($parent_themes as $parent_theme) {
                $theme->add_parent($parent_theme);
            }
            $theme_authors = $this->convert_authors_arrays_to_authors_objects($configuration['authors']);
            foreach ($theme_authors as $theme_author) {
                $theme->add_author($theme_author);
            }
            $theme_screenshots = $this->convert_screenshots_arrays_to_screenshots_objects($configuration['screenshots']);
            foreach ($theme_screenshots as $theme_screenshot) {
                $theme->add_screenshot($theme_screenshot);
            }
        }
        return $themes;
    }
    /**
     * @param array|ThemeInterface[] $themes
     */
    private function check_for_circular_dependencies(array $themes): void
    {
        try {
            foreach ($themes as $theme) {
                $this->circular_dependency_checker->check($theme);
            }
        } catch (Circular_Dependency_Found_Exception $exception) {
            throw new Theme_Loading_Failed_Exception('Circular dependency found.', 0, $exception);
        }
    }
    /**
     * @return array|ThemeInterface[]
     */
    private function convert_parents_names_to_parents_objects(string $theme_name, array $parents_names, array $existing_themes): array
    {
        return array_map(function (string $parent_name) use ($theme_name, $existing_themes): Theme_Interface {
            if (!isset($existing_themes[$parent_name])) {
                throw new Theme_Loading_Failed_Exception(sprintf('Unexisting theme "%s" is required by "%s".', $parent_name, $theme_name));
            }
            return $existing_themes[$parent_name];
        }, $parents_names);
    }
    /**
     * @return array|ThemeAuthor[]
     */
    private function convert_authors_arrays_to_authors_objects(array $authors_arrays): array
    {
        return array_map(fn(array $author_array): Theme_Author => $this->theme_author_factory->create_from_array($author_array), $authors_arrays);
    }
    /**
     * @return array|ThemeScreenshot[]
     */
    private function convert_screenshots_arrays_to_screenshots_objects(array $screenshots_arrays): array
    {
        return array_map(fn(array $screenshot_array): Theme_Screenshot => $this->theme_screenshot_factory->create_from_array($screenshot_array), $screenshots_arrays);
    }
}