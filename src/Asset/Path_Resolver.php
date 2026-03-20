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
namespace Sylius\Bundle\Theme_Bundle\Asset;

use Sylius\Bundle\Theme_Bundle\Asset\Installer\Assets_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Filesystem\Filesystem_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
final readonly class Path_Resolver implements Path_Resolver_Interface
{
    public function __construct(private Assets_Provider_Interface $assets_provider, private Filesystem_Interface $filesystem)
    {
    }
    public function resolve(string $path, string $base_path, Theme_Interface $theme): string
    {
        $base_path = rtrim($base_path, '/');
        if ($base_path === '' || !str_contains($path, $base_path)) {
            $base_path_position_at_path = 0;
            $base_path_length = 0;
        } else {
            $base_path_position_at_path = strpos($path, $base_path);
            $base_path_length = strlen($base_path);
        }
        $relative_path = trim(substr($path, $base_path_position_at_path + $base_path_length), '/');
        if ($this->should_path_be_modified($relative_path, $theme)) {
            $prefix_path = rtrim(substr($path, $base_path_position_at_path, $base_path_length), '/');
            return sprintf('%s/_themes/%s/%s', $prefix_path, $theme->get_name(), $relative_path);
        }
        return $path;
    }
    private function should_path_be_modified(string $relative_path, Theme_Interface $theme): bool
    {
        foreach ($this->assets_provider->provide_directories_for_theme($theme) as $origin_dir => $target_dir) {
            $target_dir = trim($target_dir, '/');
            if ($target_dir !== '' && !str_contains($relative_path, $target_dir)) {
                continue;
            }
            if (!$this->filesystem->exists($origin_dir . '/' . ltrim(str_replace($target_dir, '', $relative_path), '/'))) {
                continue;
            }
            return true;
        }
        return false;
    }
}