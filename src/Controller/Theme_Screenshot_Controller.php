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
namespace Sylius\Bundle\Theme_Bundle\Controller;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
use Symfony\Component\Http_Foundation\Binary_File_Response;
use Symfony\Component\Http_Foundation\File\Exception\File_Not_Found_Exception;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Http_Kernel\Exception\Not_Found_Http_Exception;
final readonly class Theme_Screenshot_Controller
{
    public function __construct(private Theme_Repository_Interface $theme_repository)
    {
    }
    public function stream_screenshot_action(string $theme_name, int $screenshot_number): Response
    {
        $screenshot_path = $this->get_screenshot_path($this->get_theme($theme_name), $screenshot_number);
        try {
            return new Binary_File_Response($screenshot_path);
        } catch (File_Not_Found_Exception $exception) {
            throw new Not_Found_Http_Exception(sprintf('Screenshot "%s" does not exist', $screenshot_path), $exception);
        }
    }
    private function get_screenshot_path(Theme_Interface $theme, int $screenshot_number): string
    {
        $screenshots = $theme->get_screenshots();
        if (!isset($screenshots[$screenshot_number])) {
            throw new Not_Found_Http_Exception(sprintf('Theme "%s" does not have screenshot #%d', $theme->get_title() ?? $theme->get_name(), $screenshot_number));
        }
        $screenshot_relative_path = $screenshots[$screenshot_number]->get_path();
        return rtrim($theme->get_path(), \DIRECTORY_SEPARATOR) . \DIRECTORY_SEPARATOR . $screenshot_relative_path;
    }
    private function get_theme(string $theme_name): Theme_Interface
    {
        $theme = $this->theme_repository->find_one_by_name($theme_name);
        if (null === $theme) {
            throw new Not_Found_Http_Exception(sprintf('Theme with name "%s" not found', $theme_name));
        }
        return $theme;
    }
}