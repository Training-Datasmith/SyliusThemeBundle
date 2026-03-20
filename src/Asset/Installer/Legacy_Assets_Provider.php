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
namespace Sylius\Bundle\Theme_Bundle\Asset\Installer;

use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Symfony\Component\Http_Kernel\Bundle\Bundle_Interface;
use Symfony\Component\Http_Kernel\Kernel_Interface;
/**
 * @deprecated Deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.
 */
final readonly class Legacy_Assets_Provider implements Assets_Provider_Interface
{
    private Kernel_Interface $kernel;
    public function __construct(private Assets_Provider_Interface $assets_provider, Kernel_Interface $kernel, private Theme_Hierarchy_Provider_Interface $theme_hierarchy_provider)
    {
        @trigger_error(sprintf('"%s" is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.', self::class), \E_USER_DEPRECATED);
        $this->kernel = $kernel;
    }
    public function provide_directories_for_theme(Theme_Interface $root_theme): iterable
    {
        $themes = array_reverse($this->theme_hierarchy_provider->get_theme_hierarchy($root_theme));
        foreach ($themes as $theme) {
            foreach ($this->kernel->get_bundles() as $bundle) {
                yield $theme->get_path() . '/' . $bundle->get_name() . '/public' => '/bundles/' . $this->get_public_bundle_name($bundle);
            }
        }
        yield from $this->assets_provider->provide_directories_for_theme($root_theme);
    }
    public function provide_directories_for_bundle(Bundle_Interface $bundle): iterable
    {
        yield from $this->assets_provider->provide_directories_for_bundle($bundle);
    }
    private function get_public_bundle_name(Bundle_Interface $bundle): string
    {
        return (string) preg_replace('/bundle$/', '', strtolower($bundle->get_name()));
    }
}