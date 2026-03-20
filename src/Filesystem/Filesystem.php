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
namespace Sylius\Bundle\Theme_Bundle\Filesystem;

use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;
final readonly class Filesystem implements Filesystem_Interface
{
    private Base_Filesystem $filesystem;
    public function __construct()
    {
        $this->filesystem = new Base_Filesystem();
    }
    public function get_file_contents(string $file): string
    {
        return (string) file_get_contents($file);
    }
    public function copy(string $origin_file, string $target_file, bool $override = false): void
    {
        $this->filesystem->copy($origin_file, $target_file, $override);
    }
    public function mkdir($dirs, int $mode = 0777): void
    {
        $this->filesystem->mkdir($dirs, $mode);
    }
    public function exists($files): bool
    {
        return $this->filesystem->exists($files);
    }
    public function touch($files, ?int $time = null, ?int $atime = null): void
    {
        $this->filesystem->touch($files, $time, $atime);
    }
    public function remove($files): void
    {
        $this->filesystem->remove($files);
    }
    public function chmod($files, int $mode, int $umask = 00, bool $recursive = false): void
    {
        $this->filesystem->chmod($files, $mode, $umask, $recursive);
    }
    public function chown($files, string $user, bool $recursive = false): void
    {
        $this->filesystem->chown($files, $user, $recursive);
    }
    public function chgrp($files, string $group, bool $recursive = false): void
    {
        $this->filesystem->chgrp($files, $group, $recursive);
    }
    public function rename(string $origin, string $target, bool $overwrite = false): void
    {
        $this->filesystem->rename($origin, $target, $overwrite);
    }
    public function symlink(string $origin_dir, string $target_dir, bool $copy_on_windows = false): void
    {
        $this->filesystem->symlink($origin_dir, $target_dir, $copy_on_windows);
    }
    public function mirror(string $origin_dir, string $target_dir, ?\Traversable $iterator = null, array $options = []): void
    {
        $this->filesystem->mirror($origin_dir, $target_dir, $iterator, $options);
    }
    public function make_path_relative(string $end_path, string $start_path): string
    {
        return $this->filesystem->make_path_relative($end_path, $start_path);
    }
    public function is_absolute_path(string $file): bool
    {
        return $this->filesystem->is_absolute_path($file);
    }
}