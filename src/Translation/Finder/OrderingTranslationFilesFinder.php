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

namespace Sylius\Bundle\ThemeBundle\Translation\Finder;

final readonly class OrderingTranslationFilesFinder implements TranslationFilesFinderInterface
{
    public function __construct(private TranslationFilesFinderInterface $translationFilesFinder)
    {
    }

    public function findTranslationFiles(string $path): array
    {
        $files = $this->translationFilesFinder->findTranslationFiles($path);

        usort($files, static function (string $firstFile, string $secondFile) use ($path): int {
            $firstFile = str_replace($path, '', $firstFile);
            $secondFile = str_replace($path, '', $secondFile);

            return (int) strpos($firstFile, 'translations') <=> (int) strpos($secondFile, 'translations');
        });

        return $files;
    }
}
