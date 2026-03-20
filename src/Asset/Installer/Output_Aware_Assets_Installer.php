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
namespace Sylius\Bundle\Theme_Bundle\Asset\Installer;

use Symfony\Component\Console\Output\Null_Output;
use Symfony\Component\Console\Output\Output_Interface;
use Symfony\Component\Http_Kernel\Bundle\Bundle_Interface;
final class Output_Aware_Assets_Installer implements Assets_Installer_Interface, Output_Aware_Interface
{
    private Output_Interface $output;
    public function __construct(private readonly Assets_Installer_Interface $assets_installer)
    {
        $this->output = new Null_Output();
    }
    public function set_output(Output_Interface $output): void
    {
        $this->output = $output;
    }
    public function install_assets(string $target_dir, int $symlink_mask): int
    {
        $this->output->writeln($this->provide_expectation_comment($symlink_mask));
        return $this->assets_installer->install_assets($target_dir, $symlink_mask);
    }
    public function install_bundle_assets(Bundle_Interface $bundle, string $target_dir, int $symlink_mask): int
    {
        $this->output->writeln(sprintf('Installing assets for <comment>%s</comment> into <comment>%s</comment>', $bundle->get_namespace(), $target_dir));
        $effective_symlink_mask = $this->assets_installer->install_bundle_assets($bundle, $target_dir, $symlink_mask);
        $this->output->writeln($this->provide_result_comment($symlink_mask, $effective_symlink_mask));
        return $effective_symlink_mask;
    }
    private function provide_result_comment(int $symlink_mask, int $effective_symlink_mask): string
    {
        if ($effective_symlink_mask === $symlink_mask) {
            switch ($symlink_mask) {
                case Assets_Installer_Interface::HARD_COPY:
                    return 'The assets were copied.';
                case Assets_Installer_Interface::SYMLINK:
                    return 'The assets were installed using symbolic links.';
                case Assets_Installer_Interface::RELATIVE_SYMLINK:
                    return 'The assets were installed using relative symbolic links.';
            }
        }
        return match ($symlink_mask + $effective_symlink_mask) {
            Assets_Installer_Interface::SYMLINK, Assets_Installer_Interface::RELATIVE_SYMLINK => 'It looks like your system doesn\'t support symbolic links, so the assets were copied.',
            Assets_Installer_Interface::RELATIVE_SYMLINK + Assets_Installer_Interface::SYMLINK => 'It looks like your system doesn\'t support relative symbolic links, so the assets were installed by using absolute symbolic links.',
            default => 'Something gone bad, can\'t provide the result of assets installing!',
        };
    }
    private function provide_expectation_comment(int $symlink_mask): string
    {
        if (Assets_Installer_Interface::HARD_COPY === $symlink_mask) {
            return 'Installing assets as <comment>hard copies</comment>.';
        }
        return 'Trying to install assets as <comment>symbolic links</comment>.';
    }
}