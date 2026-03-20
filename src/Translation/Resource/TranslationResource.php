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
namespace Sylius\Bundle\Theme_Bundle\Translation\Resource;

final readonly class Translation_Resource implements Translation_Resource_Interface
{
    private string $locale;
    private string $format;
    private string $domain;
    public function __construct(private string $name)
    {
        $parts = explode('.', basename($this->name), 3);
        if (3 !== count($parts)) {
            throw new \InvalidArgumentException(sprintf('Could not create a translation resource with filepath "%s".', $this->name));
        }
        $this->domain = $parts[0];
        $this->locale = $parts[1];
        $this->format = $parts[2];
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function get_locale(): string
    {
        return $this->locale;
    }
    public function get_format(): string
    {
        return $this->format;
    }
    public function get_domain(): string
    {
        return $this->domain;
    }
}