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

class Theme implements Theme_Interface, \Stringable
{
    protected string $name;
    /** @var string|null */
    protected $title;
    /** @var string|null */
    protected $description;
    /** @var array|ThemeAuthor[] */
    protected $authors = [];
    /** @var array|ThemeInterface[] */
    protected $parents = [];
    /** @var array|ThemeScreenshot[] */
    protected $screenshots = [];
    public function __construct(string $name, protected string $path)
    {
        $this->assert_name_is_valid($name);
        $this->name = $name;
    }
    public function __toString(): string
    {
        return $this->title ?? $this->name;
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function get_path(): string
    {
        return $this->path;
    }
    public function get_title(): ?string
    {
        return $this->title;
    }
    public function set_title(?string $title): void
    {
        $this->title = $title;
    }
    public function get_description(): ?string
    {
        return $this->description;
    }
    public function set_description(?string $description): void
    {
        $this->description = $description;
    }
    public function get_authors(): array
    {
        return $this->authors;
    }
    public function add_author(Theme_Author $author): void
    {
        $this->authors[] = $author;
    }
    public function remove_author(Theme_Author $author): void
    {
        $this->authors = array_filter($this->authors, fn(\Sylius\Bundle\Theme_Bundle\Model\Theme_Author $current_author) => $current_author !== $author);
    }
    public function get_parents(): array
    {
        return $this->parents;
    }
    public function add_parent(Theme_Interface $theme): void
    {
        $this->parents[] = $theme;
    }
    public function remove_parent(Theme_Interface $theme): void
    {
        $this->parents = array_filter($this->parents, fn(\Sylius\Bundle\Theme_Bundle\Model\Theme_Interface $current_theme) => $current_theme !== $theme);
    }
    public function get_screenshots(): array
    {
        return $this->screenshots;
    }
    public function add_screenshot(Theme_Screenshot $screenshot): void
    {
        $this->screenshots[] = $screenshot;
    }
    public function remove_screenshot(Theme_Screenshot $screenshot): void
    {
        $this->screenshots = array_filter($this->screenshots, fn(\Sylius\Bundle\Theme_Bundle\Model\Theme_Screenshot $current_screenshot) => $current_screenshot !== $screenshot);
    }
    private function assert_name_is_valid(string $name): void
    {
        $pattern = '/^[a-zA-Z0-9\-]+\/[a-zA-Z0-9\-]+$/';
        if (false === (bool) preg_match($pattern, $name)) {
            throw new \InvalidArgumentException(sprintf('Given name "%s" does not match regular expression "%s".', $name, $pattern));
        }
    }
}