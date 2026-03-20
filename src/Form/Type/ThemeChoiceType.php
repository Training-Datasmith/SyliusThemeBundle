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
namespace Sylius\Bundle\Theme_Bundle\Form\Type;

use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
use Symfony\Component\Form\Abstract_Type;
use Symfony\Component\Form\Extension\Core\Type\Choice_Type;
use Symfony\Component\Options_Resolver\Options;
use Symfony\Component\Options_Resolver\Options_Resolver;
final class Theme_Choice_Type extends Abstract_Type
{
    public function __construct(private readonly Theme_Repository_Interface $theme_repository)
    {
    }
    public function configure_options(Options_Resolver $resolver): void
    {
        $resolver->set_defaults(['choices' => fn(Options $options): array => $this->theme_repository->find_all(), 'choice_label' => function (Theme_Interface $theme): string {
            $title = $theme->get_title();
            return $title ?? $theme->get_name();
        }]);
    }
    public function get_parent(): string
    {
        return Choice_Type::class;
    }
    public function get_block_prefix(): string
    {
        return 'sylius_theme_choice';
    }
}