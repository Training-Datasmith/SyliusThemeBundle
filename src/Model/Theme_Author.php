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

final class Theme_Author
{
    private ?string $name = null;
    private ?string $email = null;
    private ?string $homepage = null;
    private ?string $role = null;
    public function get_name(): ?string
    {
        return $this->name;
    }
    public function set_name(?string $name): void
    {
        $this->name = $name;
    }
    public function get_email(): ?string
    {
        return $this->email;
    }
    public function set_email(?string $email): void
    {
        $this->email = $email;
    }
    public function get_homepage(): ?string
    {
        return $this->homepage;
    }
    public function set_homepage(?string $homepage): void
    {
        $this->homepage = $homepage;
    }
    public function get_role(): ?string
    {
        return $this->role;
    }
    public function set_role(?string $role): void
    {
        $this->role = $role;
    }
}