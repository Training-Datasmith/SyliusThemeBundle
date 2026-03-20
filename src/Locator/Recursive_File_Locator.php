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
namespace Sylius\Bundle\Theme_Bundle\Locator;

use Sylius\Bundle\Theme_Bundle\Factory\Finder_Factory_Interface;
use Symfony\Component\Finder\Spl_File_Info;
final readonly class Recursive_File_Locator implements File_Locator_Interface
{
    /**
     * @param array|string[] $paths An array of paths where to look for resources
     * @param int|null $depth Restrict depth to search for configuration file inside theme folder
     */
    public function __construct(private Finder_Factory_Interface $finder_factory, private array $paths, private ?int $depth = null)
    {
    }
    public function locate_file_named(string $name): string
    {
        return $this->do_locate_files_named($name)->current();
    }
    public function locate_files_named(string $name): array
    {
        return iterator_to_array($this->do_locate_files_named($name));
    }
    private function do_locate_files_named(string $name): \Generator
    {
        $this->assert_name_is_not_empty($name);
        $found = false;
        foreach ($this->paths as $path) {
            try {
                $finder = $this->finder_factory->create();
                if ($this->depth !== null) {
                    $finder->depth(sprintf('<= %d', $this->depth));
                }
                $finder->files()->follow_links()->name($name)->ignore_unreadable_dirs()->in($path);
                /** @var SplFileInfo $file */
                foreach ($finder as $file) {
                    $found = true;
                    yield $file->get_pathname();
                }
            } catch (\InvalidArgumentException) {
            }
        }
        if (false === $found) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not exist (searched in the following directories: %s).', $name, implode(', ', $this->paths)));
        }
    }
    private function assert_name_is_not_empty(string $name): void
    {
        if ('' === $name) {
            throw new \InvalidArgumentException('An empty file name is not valid to be located.');
        }
    }
}