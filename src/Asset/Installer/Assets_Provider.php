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
final readonly class Assets_Provider implements Assets_Provider_Interface
{
    private Kernel_Interface $kernel;
    public function __construct(Kernel_Interface $kernel, private Theme_Hierarchy_Provider_Interface $theme_hierarchy_provider)
    {
        $this->kernel = $kernel;
    }
    public function provide_directories_for_theme(Theme_Interface $root_theme): iterable
    {
        foreach ($this->kernel->get_bundles() as $bundle) {
            yield from $this->provide_directories_for_bundle($bundle);
        }
        $themes = array_reverse($this->theme_hierarchy_provider->get_theme_hierarchy($root_theme));
        foreach ($themes as $theme) {
            yield $theme->get_path() . '/public' => '/';
        }
    }
    public function provide_directories_for_bundle(Bundle_Interface $bundle): iterable
    {
        yield $bundle->get_path() . '/Resources/public' => '/bundles/' . $this->get_public_bundle_name($bundle);
        yield $bundle->get_path() . '/public' => '/bundles/' . $this->get_public_bundle_name($bundle);
    }
    private function get_public_bundle_name(Bundle_Interface $bundle): string
    {
        return (string) preg_replace('/bundle$/', '', strtolower($bundle->get_name()));
    }
}