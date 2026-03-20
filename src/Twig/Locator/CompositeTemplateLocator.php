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
final readonly class Composite_Template_Locator implements Template_Locator_Interface
{
    /**
     * @psalm-param iterable<TemplateLocatorInterface> $themedTemplateLocators
     * @param iterable|TemplateLocatorInterface[] $themedTemplateLocators
     */
    public function __construct(
        /**
         * @psalm-var iterable<TemplateLocatorInterface>
         */
        private iterable $themed_template_locators
    )
    {
    }
    public function locate(string $template, Theme_Interface $theme): string
    {
        foreach ($this->themed_template_locators as $themed_template_locator) {
            if (!$themed_template_locator->supports($template)) {
                continue;
            }
            try {
                return $themed_template_locator->locate($template, $theme);
            } catch (Template_Not_Found_Exception) {
                // Do nothing.
            }
        }
        throw new Template_Not_Found_Exception($template, [$theme]);
    }
    public function supports(string $template): bool
    {
        foreach ($this->themed_template_locators as $themed_template_locator) {
            if ($themed_template_locator->supports($template)) {
                return true;
            }
        }
        return false;
    }
}