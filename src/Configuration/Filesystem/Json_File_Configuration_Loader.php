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

use Sylius\Bundle\Theme_Bundle\Filesystem\Filesystem_Interface;
final readonly class Json_File_Configuration_Loader implements Configuration_Loader_Interface
{
    public function __construct(private Filesystem_Interface $filesystem)
    {
    }
    public function load(string $identifier): array
    {
        $this->assert_file_exists($identifier);
        $contents = $this->filesystem->get_file_contents($identifier);
        return array_merge(['path' => dirname($identifier)], json_decode($contents, true));
    }
    private function assert_file_exists(string $path): void
    {
        if (!$this->filesystem->exists($path)) {
            throw new \InvalidArgumentException(sprintf('Given file "%s" does not exist!', $path));
        }
    }
}