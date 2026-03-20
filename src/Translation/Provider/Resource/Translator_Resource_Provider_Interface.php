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
namespace Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource;

use Sylius\Bundle\Theme_Bundle\Translation\Resource\Translation_Resource_Interface;
interface Translator_Resource_Provider_Interface
{
    /**
     * @return array|TranslationResourceInterface[]
     */
    public function get_resources(): array;
    /**
     * @return array|string[]
     */
    public function get_resources_locales(): array;
}