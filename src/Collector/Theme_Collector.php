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
namespace Sylius\Bundle\Theme_Bundle\Collector;

use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Sylius\Bundle\Theme_Bundle\Hierarchy_Provider\Theme_Hierarchy_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Model\Theme_Interface;
use Sylius\Bundle\Theme_Bundle\Repository\Theme_Repository_Interface;
use Symfony\Component\Http_Foundation\Request;
use Symfony\Component\Http_Foundation\Response;
use Symfony\Component\Http_Kernel\Data_Collector\Data_Collector;
/**
 * @property $data array{used_theme: ?ThemeInterface, used_themes: ThemeInterface[], themes: ThemeInterface[]}
 */
final class Theme_Collector extends Data_Collector
{
    public function __construct(private readonly Theme_Repository_Interface $theme_repository, private readonly Theme_Context_Interface $theme_context, private readonly Theme_Hierarchy_Provider_Interface $theme_hierarchy_provider)
    {
        $this->data = ['used_theme' => null, 'used_themes' => [], 'themes' => []];
    }
    public function get_used_theme(): ?Theme_Interface
    {
        return $this->data['used_theme'];
    }
    /**
     * @return ThemeInterface[]
     */
    public function get_used_themes(): array
    {
        return $this->data['used_themes'];
    }
    /**
     * @return ThemeInterface[]
     */
    public function get_themes(): array
    {
        return $this->data['themes'];
    }
    public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {
        $used_theme = $this->theme_context->get_theme();
        /** @var ThemeInterface[] $usedThemes */
        $used_themes = null !== $used_theme ? $this->theme_hierarchy_provider->get_theme_hierarchy($used_theme) : [];
        /** @var ThemeInterface[] $themes */
        $themes = $this->theme_repository->find_all();
        $this->data['used_theme'] = $used_theme;
        $this->data['used_themes'] = $used_themes;
        $this->data['themes'] = $themes;
    }
    public function reset(): void
    {
        $this->data['used_theme'] = null;
        $this->data['used_themes'] = [];
        $this->data['themes'] = [];
    }
    public function get_name(): string
    {
        return 'sylius_theme';
    }
}