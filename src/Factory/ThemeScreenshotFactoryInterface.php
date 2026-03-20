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
namespace Sylius\Bundle\Theme_Bundle\Factory;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Screenshot;
interface Theme_Screenshot_Factory_Interface
{
    public function create_from_array(array $data): Theme_Screenshot;
}