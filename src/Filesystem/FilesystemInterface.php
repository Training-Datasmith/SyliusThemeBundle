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

use Symfony\Component\Filesystem\Exception\File_Not_Found_Exception;
use Symfony\Component\Filesystem\Exception\Io_Exception;
interface Filesystem_Interface
{
    /**
     * Copies a file.
     *
     * This method only copies the file if the origin file is newer than the target file.
     *
     * By default, if the target already exists, it is not overridden.
     *
     * @throws FileNotFoundException When originFile doesn't exist
     * @throws IOException           When copy fails
     */
    public function copy(string $origin_file, string $target_file, bool $override = false);
    /**
     * Creates a directory recursively.
     *
     * @param string|array|\Traversable $dirs The directory path
     *
     * @throws IOException On any directory creation failure
     */
    public function mkdir($dirs, int $mode = 0777);
    /**
     * Checks the existence of files or directories.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to check
     *
     * @return bool true if the file exists, false otherwise
     */
    public function exists($files);
    /**
     * Sets access and modification time of file.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to create
     *
     * @throws IOException When touch fails
     */
    public function touch($files, ?int $time = null, ?int $atime = null);
    /**
     * Removes files or directories.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to remove
     *
     * @throws IOException When removal fails
     */
    public function remove($files);
    /**
     * Change mode for an array of files or directories.
     *
     * @param string|array|\Traversable $files     A filename, an array of files, or a \Traversable instance to change mode
     *
     * @throws IOException When the change fail
     */
    public function chmod($files, int $mode, int $umask = 00, bool $recursive = false);
    /**
     * Change the owner of an array of files or directories.#
     *
     * @param string|array|\Traversable $files     A filename, an array of files, or a \Traversable instance to change owner
     *
     * @throws IOException When the change fail
     */
    public function chown($files, string $user, bool $recursive = false);
    /**
     * Change the group of an array of files or directories.
     *
     * @param string|array|\Traversable $files     A filename, an array of files, or a \Traversable instance to change group
     *
     * @throws IOException When the change fail
     */
    public function chgrp($files, string $group, bool $recursive = false);
    /**
     * Renames a file or a directory.
     *
     * @throws IOException When target file or directory already exists
     * @throws IOException When origin cannot be renamed
     */
    public function rename(string $origin, string $target, bool $overwrite = false);
    /**
     * Creates a symbolic link or copy a directory.
     *
     * @throws IOException When symlink fails
     */
    public function symlink(string $origin_dir, string $target_dir, bool $copy_on_windows = false);
    /**
     * Mirrors a directory to another.
     *
     * @param \Traversable $iterator  A Traversable instance
     * @param array        $options   An array of boolean options
     *                                Valid options are:
     *                                - $options['override'] Whether to override an existing file on copy or not (see copy())
     *                                - $options['copy_on_windows'] Whether to copy files instead of links on Windows (see symlink())
     *                                - $options['delete'] Whether to delete files that are not in the source directory (defaults to false)
     *
     * @throws IOException When file type is unknown
     */
    public function mirror(string $origin_dir, string $target_dir, ?\Traversable $iterator = null, array $options = []);
    /**
     * Given an existing path, convert it to a path relative to a given starting path.
     *
     * @return string Path of target relative to starting path
     */
    public function make_path_relative(string $end_path, string $start_path);
    /**
     * Returns whether the file path is an absolute path.
     *
     * @return bool
     */
    public function is_absolute_path(string $file);
    public function get_file_contents(string $file): string;
}