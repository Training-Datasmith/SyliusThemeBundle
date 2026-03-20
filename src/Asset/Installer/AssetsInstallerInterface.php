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

use Symfony\Component\Http_Kernel\Bundle\Bundle_Interface;
interface Assets_Installer_Interface
{
    /**
     * Constant used as parameter and returned in installAssets() methods.
     *
     * @see AssetsInstallerInterface::installAssets()
     * @see AssetsInstallerInterface::installBundleAssets()
     * @see AssetsInstallerInterface::installDirAssets()
     */
    public const HARD_COPY = 0;
    /**
     * Constant used as parameter and returned in installAssets() methods.
     *
     * @see AssetsInstallerInterface::installAssets()
     * @see AssetsInstallerInterface::installBundleAssets()
     * @see AssetsInstallerInterface::installDirAssets()
     */
    public const SYMLINK = 1;
    /**
     * Constant used as parameter and returned in installAssets() methods.
     *
     * @see AssetsInstallerInterface::installAssets()
     * @see AssetsInstallerInterface::installBundleAssets()
     * @see AssetsInstallerInterface::installDirAssets()
     */
    public const RELATIVE_SYMLINK = 2;
    /**
     * @return int Effective symlink mask (lowest value received from installBundleAssets() method)
     */
    public function install_assets(string $target_dir, int $symlink_mask): int;
    /**
     * @return int Effective symlink mask (lowest value received from installDirAssets() method)
     */
    public function install_bundle_assets(Bundle_Interface $bundle, string $target_dir, int $symlink_mask): int;
}