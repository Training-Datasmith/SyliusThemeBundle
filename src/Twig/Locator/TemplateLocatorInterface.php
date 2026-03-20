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
namespace Sylius\Bundle\Theme_Bundle\Twig\Locator;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
interface Template_Locator_Interface
{
    /**
     * @throws TemplateNotFoundException
     */
    public function locate(string $template, Theme_Interface $theme): string;
    public function supports(string $template): bool;
}