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
namespace Sylius\Bundle\Theme_Bundle\Loader;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
final class Circular_Dependency_Found_Exception extends \DomainException
{
    /**
     * @param ThemeInterface[] $themes
     */
    public function __construct(array $themes, ?\Throwable $previous = null)
    {
        $cycle = $this->get_cycle_from_array($themes);
        $message = sprintf('Circular dependency was found while resolving theme "%s", caused by cycle "%s".', $this->get_first_theme($themes)->get_name(), $this->format_cycle_to_string($cycle));
        parent::__construct($message, 0, $previous);
    }
    private function get_cycle_from_array(array $themes): array
    {
        while (reset($themes) !== end($themes) || 1 === count($themes)) {
            array_shift($themes);
        }
        if (0 === count($themes)) {
            throw new \InvalidArgumentException('There is no cycle within given themes.');
        }
        return $themes;
    }
    private function format_cycle_to_string(array $themes): string
    {
        $themes_names = array_map(fn(Theme_Interface $theme) => $theme->get_name(), $themes);
        return implode(' -> ', $themes_names);
    }
    /**
     * @param ThemeInterface[] $themes
     */
    private function get_first_theme(array $themes): Theme_Interface
    {
        /** @var ThemeInterface $theme */
        $theme = reset($themes);
        return $theme;
    }
}