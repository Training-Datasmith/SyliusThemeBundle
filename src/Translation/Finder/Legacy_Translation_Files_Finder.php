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

use Sylius\Bundle\Theme_Bundle\Factory\Finder_Factory_Interface;
use Symfony\Component\Finder\Spl_File_Info;
/**
 * @deprecated Deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.
 */
final readonly class Legacy_Translation_Files_Finder implements Translation_Files_Finder_Interface
{
    public function __construct(private Finder_Factory_Interface $finder_factory)
    {
        @trigger_error(sprintf('"%s" is deprecated since Sylius/ThemeBundle 2.0 and will be removed in 3.0.', self::class), \E_USER_DEPRECATED);
    }
    public function find_translation_files(string $path): array
    {
        $theme_files = $this->get_files($path);
        $translations_files = [];
        foreach ($theme_files as $theme_file) {
            $theme_filepath = (string) $theme_file;
            if (!$this->is_translation_file($theme_filepath)) {
                continue;
            }
            $translations_files[] = $theme_filepath;
        }
        return $translations_files;
    }
    /**
     * @return iterable|SplFileInfo[]
     */
    private function get_files(string $path): iterable
    {
        $finder = $this->finder_factory->create();
        $finder->ignore_unreadable_dirs()->in($path);
        return $finder;
    }
    private function is_translation_file(string $file): bool
    {
        return str_contains($file, 'translations' . \DIRECTORY_SEPARATOR) && (bool) preg_match('/^[^\.]+?\.[a-zA-Z_]{2,}?\.[a-z0-9]{2,}?$/', basename($file));
    }
}