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

use Sylius\Bundle\ThemeBundle\Configuration\ConfigurationProviderInterface;
use Sylius\Bundle\ThemeBundle\Locator\FileLocatorInterface;

final readonly class FilesystemConfigurationProvider implements ConfigurationProviderInterface
{
    public function __construct(private FileLocatorInterface $fileLocator, private ConfigurationLoaderInterface $loader, private string $configurationFilename)
    {
    }

    public function getConfigurations(): array
    {
        try {
            return array_map(
                $this->loader->load(...),
                $this->fileLocator->locateFilesNamed($this->configurationFilename),
            );
        } catch (\InvalidArgumentException) {
            return [];
        }
    }
}
