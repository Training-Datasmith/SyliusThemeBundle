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
namespace Sylius\Bundle\Theme_Bundle\Command;

use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
use Symfony\Component\Console\Attribute\As_Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Output\Output_Interface;
#[As_Command(name: 'sylius:theme:list', description: 'Shows list of detected themes.')]
final class List_Command extends Command
{
    public function __construct(private readonly Theme_Repository_Interface $theme_repository)
    {
        parent::__construct();
    }
    protected function execute(Input_Interface $input, Output_Interface $output): int
    {
        $themes = $this->theme_repository->find_all();
        if (0 === count($themes)) {
            $output->writeln('<error>There are no themes.</error>');
            return 0;
        }
        $output->writeln('<question>Successfully loaded themes:</question>');
        $table = new Table($output);
        $table->set_headers(['Title', 'Name', 'Path']);
        foreach ($themes as $theme) {
            $table->add_row([$theme->get_title(), $theme->get_name(), $theme->get_path()]);
        }
        $table->set_style('borderless');
        $table->render();
        return 0;
    }
}