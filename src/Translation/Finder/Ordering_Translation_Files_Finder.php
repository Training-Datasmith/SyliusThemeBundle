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
namespace Sylius\Bundle\Theme_Bundle\Translation\Finder;

final readonly class Ordering_Translation_Files_Finder implements Translation_Files_Finder_Interface
{
    public function __construct(private Translation_Files_Finder_Interface $translation_files_finder)
    {
    }
    public function find_translation_files(string $path): array
    {
        $files = $this->translation_files_finder->find_translation_files($path);
        usort($files, static function (string $first_file, string $second_file) use ($path): int {
            $first_file = str_replace($path, '', $first_file);
            $second_file = str_replace($path, '', $second_file);
            return (int) strpos($first_file, 'translations') <=> (int) strpos($second_file, 'translations');
        });
        return $files;
    }
}