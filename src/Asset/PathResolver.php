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

namespace Sylius\Bundle\ThemeBundle\Asset;

use Sylius\Bundle\ThemeBundle\Asset\Installer\AssetsProviderInterface;
use Sylius\Bundle\ThemeBundle\Filesystem\FilesystemInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;

final readonly class PathResolver implements PathResolverInterface
{
    public function __construct(private AssetsProviderInterface $assetsProvider, private FilesystemInterface $filesystem)
    {
    }

    public function resolve(string $path, string $basePath, ThemeInterface $theme): string
    {
        $basePath = rtrim($basePath, '/');

        if ($basePath === '' || !str_contains($path, $basePath)) {
            $basePathPositionAtPath = 0;
            $basePathLength = 0;
        } else {
            $basePathPositionAtPath = strpos($path, $basePath);
            $basePathLength = strlen($basePath);
        }

        $relativePath = trim(substr($path, $basePathPositionAtPath + $basePathLength), '/');

        if ($this->shouldPathBeModified($relativePath, $theme)) {
            $prefixPath = rtrim(substr($path, $basePathPositionAtPath, $basePathLength), '/');

            return sprintf('%s/_themes/%s/%s', $prefixPath, $theme->getName(), $relativePath);
        }

        return $path;
    }

    private function shouldPathBeModified(string $relativePath, ThemeInterface $theme): bool
    {
        foreach ($this->assetsProvider->provideDirectoriesForTheme($theme) as $originDir => $targetDir) {
            $targetDir = trim($targetDir, '/');

            if ($targetDir !== '' && !str_contains($relativePath, $targetDir)) {
                continue;
            }

            if (!$this->filesystem->exists($originDir . '/' . ltrim(str_replace($targetDir, '', $relativePath), '/'))) {
                continue;
            }

            return true;
        }

        return false;
    }
}
