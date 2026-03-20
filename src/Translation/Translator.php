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
namespace Sylius\Bundle\Theme_Bundle\Translation;

use Sylius\Bundle\Theme_Bundle\Translation\Provider\Loader\Translator_Loader_Provider_Interface;
use Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource\Translator_Resource_Provider_Interface;
use Symfony\Component\Http_Kernel\Cache_Warmer\Warmable_Interface;
use Symfony\Component\Translation\Formatter\Message_Formatter_Interface;
use Symfony\Component\Translation\Translator as BaseTranslator;
final class Translator extends Base_Translator implements Warmable_Interface
{
    protected array $options = ['cache_dir' => null, 'debug' => false];
    private bool $resources_loaded = false;
    public function __construct(private readonly Translator_Loader_Provider_Interface $loader_provider, private readonly Translator_Resource_Provider_Interface $resource_provider, Message_Formatter_Interface $message_formatter, string $locale, array $options = [])
    {
        $this->assert_options_are_known($options);
        $this->options = array_merge($this->options, $options);
        if (null !== $this->options['cache_dir'] && $this->options['debug']) {
            $this->add_resources();
        }
        parent::__construct($locale, $message_formatter, $this->options['cache_dir'], $this->options['debug']);
    }
    public function warm_up($cache_dir, ?string $build_dir = null): array
    {
        // skip warmUp when translator doesn't use cache
        if (null === $this->options['cache_dir']) {
            return [];
        }
        $locales = array_merge($this->get_fallback_locales(), [$this->get_locale()], $this->resource_provider->get_resources_locales());
        foreach (array_unique($locales) as $locale) {
            // reset catalogue in case it's already loaded during the dump of the other locales.
            if (isset($this->catalogues[$locale])) {
                unset($this->catalogues[$locale]);
            }
            $this->load_catalogue($locale);
        }
        return [];
    }
    /**
     * @param string $locale
     */
    protected function initialize_catalogue($locale): void
    {
        $this->initialize();
        parent::initialize_catalogue($locale);
    }
    /**
     * @param string $locale
     */
    protected function compute_fallback_locales($locale): array
    {
        $theme_modifier = $this->get_locale_modifier($locale);
        $locale_without_modifier = $this->get_locale_without_modifier($locale, $theme_modifier);
        $computed_fallback_locales = parent::compute_fallback_locales($locale);
        array_unshift($computed_fallback_locales, $locale_without_modifier);
        $fallback_locales = [];
        foreach (array_diff($computed_fallback_locales, [$locale]) as $computed_fallback) {
            $fallback = $computed_fallback . $theme_modifier;
            if ('' !== $theme_modifier && $locale !== $fallback) {
                $fallback_locales[] = $fallback;
            }
            $fallback_locales[] = $computed_fallback;
        }
        return array_unique($fallback_locales);
    }
    private function get_locale_modifier(string $locale): string
    {
        $modifier = strrchr($locale, '@');
        return $modifier !== false ? $modifier : '';
    }
    private function get_locale_without_modifier(string $locale, string $modifier): string
    {
        return str_replace($modifier, '', $locale);
    }
    private function initialize(): void
    {
        $this->add_resources();
        $this->add_loaders();
    }
    private function add_resources(): void
    {
        if ($this->resources_loaded) {
            return;
        }
        $resources = $this->resource_provider->get_resources();
        foreach ($resources as $resource) {
            $this->add_resource($resource->get_format(), $resource->get_name(), $resource->get_locale(), $resource->get_domain());
        }
        $this->resources_loaded = true;
    }
    private function add_loaders(): void
    {
        $loaders = $this->loader_provider->get_loaders();
        foreach ($loaders as $alias => $loader) {
            $this->add_loader($alias, $loader);
        }
    }
    private function assert_options_are_known(array $options): void
    {
        if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('The Translator does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }
    }
}