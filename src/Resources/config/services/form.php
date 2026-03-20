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
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Sylius\Bundle\Theme_Bundle\Form\Type\Theme_Choice_Type;
use Sylius\Bundle\Theme_Bundle\Form\Type\Theme_Name_Choice_Type;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Theme_Choice_Type::class)->args([service('sylius.repository.theme')])->tag('form.type');
    $services->set(Theme_Name_Choice_Type::class)->args([service('sylius.repository.theme')])->tag('form.type');
};