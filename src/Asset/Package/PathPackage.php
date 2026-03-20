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
namespace Sylius\Bundle\Theme_Bundle\Asset\Package;

use Sylius\Bundle\Theme_Bundle\Asset\Path_Resolver_Interface;
use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Symfony\Component\Asset\Context\Context_Interface;
use Symfony\Component\Asset\Path_Package as BasePathPackage;
use Symfony\Component\Asset\Version_Strategy\Version_Strategy_Interface;
/**
 * @see BasePathPackage
 */
class Path_Package extends Base_Path_Package
{
    public function __construct(string $base_path, Version_Strategy_Interface $version_strategy, protected Theme_Context_Interface $theme_context, protected Path_Resolver_Interface $path_resolver, ?Context_Interface $context = null)
    {
        parent::__construct($base_path, $version_strategy, $context);
    }
    /**
     * @param string $path
     */
    public function get_url($path): string
    {
        if ($this->is_absolute_url($path)) {
            return $path;
        }
        $theme = $this->theme_context->get_theme();
        if (null !== $theme) {
            $path = $this->path_resolver->resolve($path, $this->get_base_path(), $theme);
        }
        $versioned_path = $this->get_version_strategy()->apply_version($path);
        // if absolute or begins with /, we're done
        if ($this->is_absolute_url($versioned_path) || $versioned_path && '/' === $versioned_path[0]) {
            return $versioned_path;
        }
        return $this->get_base_path() . ltrim((string) $versioned_path, '/');
    }
}