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

final class Theme_Screenshot
{
    private ?string $title = null;
    private ?string $description = null;
    public function __construct(private readonly string $path)
    {
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
}