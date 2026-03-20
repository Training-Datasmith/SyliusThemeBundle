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
namespace Sylius\Bundle\Theme_Bundle\Model;

interface Theme_Interface
{
    public function get_name(): string;
    public function get_path(): string;
    public function get_title(): ?string;
    public function set_title(?string $title): void;
    public function get_description(): ?string;
    public function set_description(?string $description): void;
    /**
     * @return array|ThemeAuthor[]
     */
    public function get_authors(): array;
    public function add_author(Theme_Author $author): void;
    public function remove_author(Theme_Author $author): void;
    /**
     * @return array|ThemeInterface[]
     */
    public function get_parents(): array;
    public function add_parent(self $theme): void;
    public function remove_parent(self $theme): void;
    /**
     * @return array|ThemeScreenshot[]
     */
    public function get_screenshots(): array;
    public function add_screenshot(Theme_Screenshot $screenshot): void;
    public function remove_screenshot(Theme_Screenshot $screenshot): void;
}