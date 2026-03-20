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

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Symfony\Component\Http_Kernel\Bundle\Bundle_Interface;
interface Assets_Provider_Interface
{
    /**
     * @psalm-return iterable<string, string> Maps origin dir to relative target dir
     */
    public function provide_directories_for_theme(Theme_Interface $root_theme): iterable;
    /**
     * @psalm-return iterable<string, string> Maps origin dir to relative target dir
     */
    public function provide_directories_for_bundle(Bundle_Interface $bundle): iterable;
}