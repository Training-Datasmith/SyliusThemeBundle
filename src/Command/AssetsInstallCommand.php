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

use Sylius\Bundle\Theme_Bundle\Asset\Installer\Assets_Installer_Interface;
use Sylius\Bundle\Theme_Bundle\Asset\Installer\Output_Aware_Interface;
use Symfony\Component\Console\Attribute\As_Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\Input_Argument;
use Symfony\Component\Console\Input\Input_Interface;
use Symfony\Component\Console\Input\Input_Option;
use Symfony\Component\Console\Output\Output_Interface;
/**
 * Command that places themes web assets into a given directory.
 */
#[As_Command(name: 'sylius:theme:assets:install', description: 'Installs themes web assets under a public web directory')]
final class Assets_Install_Command extends Command
{
    public function __construct(private readonly Assets_Installer_Interface $assets_installer, private readonly string $project_dir)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this->set_definition([new Input_Argument('target', Input_Argument::OPTIONAL, 'The target directory')])->add_option('symlink', null, Input_Option::VALUE_NONE, 'Symlinks the assets instead of copying it')->add_option('relative', null, Input_Option::VALUE_NONE, 'Make relative symlinks')->set_help($this->get_help_message());
    }
    /**
     * @throws \InvalidArgumentException When the target directory does not exist or symlink cannot be used
     */
    protected function execute(Input_Interface $input, Output_Interface $output): int
    {
        if ($this->assets_installer instanceof Output_Aware_Interface) {
            $this->assets_installer->set_output($output);
        }
        $symlink_mask = Assets_Installer_Interface::HARD_COPY;
        if ($input->get_option('symlink')) {
            $symlink_mask = max($symlink_mask, Assets_Installer_Interface::SYMLINK);
        }
        if ($input->get_option('relative')) {
            $symlink_mask = max($symlink_mask, Assets_Installer_Interface::RELATIVE_SYMLINK);
        }
        $this->assets_installer->install_assets($this->get_target_dir($input), $symlink_mask);
        return 0;
    }
    private function get_target_dir(Input_Interface $input): string
    {
        /** @var string|null $targetDirectory */
        $target_directory = $input->get_argument('target');
        $target_directory = rtrim((string) $target_directory, '/');
        if (!$target_directory) {
            $target_directory = $this->get_public_directory();
        }
        if (!is_dir($target_directory)) {
            $target_directory = $this->project_dir . '/' . $target_directory;
            if (!is_dir($target_directory)) {
                throw new InvalidArgumentException(sprintf('The target directory "%s" does not exist.', $target_directory));
            }
        }
        return $target_directory;
    }
    private function get_help_message(): string
    {
        return <<<EOT
        The <info>%command.name%</info> command installs theme assets into a given
        directory (e.g. the <comment>public</comment> directory).
        
          <info>php %command.full_name% public</info>
        
        A "themes" directory will be created inside the target directory.
        
        To create a symlink to each theme instead of copying its assets, use the
        <info>--symlink</info> option (will fall back to hard copies when symbolic links aren't possible):
        
          <info>php %command.full_name% public --symlink</info>
        
        To make symlink relative, add the <info>--relative</info> option:
        
          <info>php %command.full_name% public --symlink --relative</info>
        
        EOT;
    }
    /**
     * @see \Symfony\Bundle\FrameworkBundle\Command\AssetsInstallCommand::getPublicDirectory()
     */
    private function get_public_directory(): string
    {
        $default_public_dir = 'public';
        $composer_file_path = $this->project_dir . '/composer.json';
        if (!file_exists($composer_file_path)) {
            return $default_public_dir;
        }
        $composer_config = json_decode((string) file_get_contents($composer_file_path), true);
        return $composer_config['extra']['public-dir'] ?? $default_public_dir;
    }
}