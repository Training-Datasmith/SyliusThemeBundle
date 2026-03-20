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
namespace Sylius\Bundle\Theme_Bundle\Configuration\Filesystem;

use Sylius\Bundle\Theme_Bundle\Configuration\Configuration_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Locator\File_Locator_Interface;
final readonly class Filesystem_Configuration_Provider implements Configuration_Provider_Interface
{
    public function __construct(private File_Locator_Interface $file_locator, private Configuration_Loader_Interface $loader, private string $configuration_filename)
    {
    }
    public function get_configurations(): array
    {
        try {
            return array_map($this->loader->load(...), $this->file_locator->locate_files_named($this->configuration_filename));
        } catch (\InvalidArgumentException) {
            return [];
        }
    }
}