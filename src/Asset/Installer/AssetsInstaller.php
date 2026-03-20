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

use Sylius\Bundle\Theme_Bundle\Asset\Path_Resolver_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
use Symfony\Component\Filesystem\Exception\Io_Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Http_Kernel\Bundle\Bundle_Interface;
use Symfony\Component\Http_Kernel\Kernel_Interface;
final readonly class Assets_Installer implements Assets_Installer_Interface
{
    private Kernel_Interface $kernel;
    public function __construct(private Filesystem $filesystem, Kernel_Interface $kernel, private Theme_Repository_Interface $theme_repository, private Path_Resolver_Interface $path_resolver, private Assets_Provider_Interface $assets_provider)
    {
        $this->kernel = $kernel;
    }
    public function install_assets(string $target_dir, int $symlink_mask): int
    {
        $effective_symlink_mask = $symlink_mask;
        foreach ($this->kernel->get_bundles() as $bundle) {
            $effective_symlink_mask = min($effective_symlink_mask, $this->install_bundle_assets($bundle, $target_dir, $symlink_mask));
        }
        foreach ($this->theme_repository->find_all() as $theme) {
            $effective_symlink_mask = min($effective_symlink_mask, $this->install_theme_assets($theme, $target_dir, $symlink_mask));
        }
        return $effective_symlink_mask;
    }
    public function install_bundle_assets(Bundle_Interface $bundle, string $target_dir, int $symlink_mask): int
    {
        $effective_symlink_mask = $symlink_mask;
        foreach ($this->assets_provider->provide_directories_for_bundle($bundle) as $origin_dir => $relative_target_dir) {
            $effective_symlink_mask = min($effective_symlink_mask, $this->do_install_assets($origin_dir, $target_dir . $relative_target_dir, $symlink_mask));
        }
        return $effective_symlink_mask;
    }
    public function install_theme_assets(Theme_Interface $theme, string $target_dir, int $symlink_mask): int
    {
        $effective_symlink_mask = $symlink_mask;
        $target_dir = $this->path_resolver->resolve($target_dir, $target_dir, $theme);
        foreach ($this->assets_provider->provide_directories_for_theme($theme) as $origin_dir => $relative_target_dir) {
            $effective_symlink_mask = min($effective_symlink_mask, $this->do_install_assets($origin_dir, $target_dir . $relative_target_dir, $symlink_mask));
        }
        return $effective_symlink_mask;
    }
    private function do_install_assets(string $origin_dir, string $target_dir, int $symlink_mask): int
    {
        if (!is_dir($origin_dir)) {
            return $symlink_mask;
        }
        if (!is_dir($target_dir)) {
            $this->filesystem->mkdir($target_dir);
        }
        $effective_symlink_mask = $symlink_mask;
        $finder = new Finder();
        $finder->sort_by_name()->ignore_dot_files(false)->in($origin_dir);
        foreach ($finder as $origin_file) {
            $target_file = rtrim($target_dir, '/') . '/' . $origin_file->get_relative_pathname();
            $this->filesystem->mkdir(dirname($target_file));
            $effective_symlink_mask = min($effective_symlink_mask, $this->install_asset($origin_file->get_pathname(), $target_file, $symlink_mask));
        }
        return $effective_symlink_mask;
    }
    private function install_asset(string $origin, string $target, int $symlink_mask): int
    {
        if (is_file($target)) {
            $this->filesystem->remove($target);
        }
        if (Assets_Installer_Interface::RELATIVE_SYMLINK === $symlink_mask) {
            try {
                $target_dirname = (string) realpath(is_dir($target) ? $target : dirname($target));
                $relative_origin = rtrim($this->filesystem->make_path_relative($origin, $target_dirname), '/');
                $this->do_install_asset($relative_origin, $target, true);
                return Assets_Installer_Interface::RELATIVE_SYMLINK;
            } catch (Io_Exception) {
                // Do nothing, trying to create non-relative symlinks later.
            }
        }
        if (Assets_Installer_Interface::HARD_COPY !== $symlink_mask) {
            try {
                $this->do_install_asset($origin, $target, true);
                return Assets_Installer_Interface::SYMLINK;
            } catch (Io_Exception) {
                // Do nothing, hard copy later.
            }
        }
        $this->do_install_asset($origin, $target, false);
        return Assets_Installer_Interface::HARD_COPY;
    }
    /**
     * @throws IOException When failed to make symbolic link, if requested.
     */
    private function do_install_asset(string $origin, string $target, bool $symlink): void
    {
        if ($symlink) {
            $this->do_symlink_asset($origin, $target);
            return;
        }
        $this->do_copy_asset($origin, $target);
    }
    /**
     * @throws IOException If symbolic link is broken
     */
    private function do_symlink_asset(string $origin, string $target): void
    {
        $this->filesystem->symlink($origin, $target);
        if (!file_exists($target)) {
            throw new Io_Exception('Symbolic link is broken');
        }
    }
    private function do_copy_asset(string $origin, string $target): void
    {
        if (is_dir($origin)) {
            $this->filesystem->mkdir($target, 0777);
            $this->filesystem->mirror($origin, $target, Finder::create()->ignore_dot_files(false)->in($origin));
            return;
        }
        $this->filesystem->copy($origin, $target);
    }
}