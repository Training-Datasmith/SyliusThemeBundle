<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ThemeBundle\Configuration\Filesystem;

use Sylius\Bundle\ThemeBundle\Filesystem\FilesystemInterface;

final readonly class JsonFileConfigurationLoader implements ConfigurationLoaderInterface
{
    public function __construct(private FilesystemInterface $filesystem)
    {
    }

    public function load(string $identifier): array
    {
        $this->assertFileExists($identifier);

        $contents = $this->filesystem->getFileContents($identifier);

        return array_merge(
            ['path' => dirname($identifier)],
            json_decode($contents, true),
        );
    }

    private function assertFileExists(string $path): void
    {
        if (!$this->filesystem->exists($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Given file "%s" does not exist!',
                $path,
            ));
        }
    }
}
