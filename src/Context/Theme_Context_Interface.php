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
namespace Sylius\Bundle\Theme_Bundle\Context;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
interface Theme_Context_Interface
{
    /**
     * Should not throw any exception if failed to get theme.
     */
    public function get_theme(): ?Theme_Interface;
}