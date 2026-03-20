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

use Sylius\Bundle\Theme_Bundle\Context\Theme_Context_Interface;
use Symfony\Component\Http_Kernel\Cache_Warmer\Warmable_Interface;
use Symfony\Component\Translation\Message_Catalogue_Interface;
use Symfony\Component\Translation\Translator_Bag_Interface;
use Symfony\Contracts\Translation\Locale_Aware_Interface;
use Symfony\Contracts\Translation\Translator_Interface;
final readonly class Theme_Aware_Translator implements Translator_Interface, Translator_Bag_Interface, Warmable_Interface, Locale_Aware_Interface
{
    /** @var TranslatorInterface&LocaleAwareInterface&TranslatorBagInterface */
    private Translator_Interface $translator;
    /**
     * @param TranslatorInterface&LocaleAwareInterface&TranslatorBagInterface $translator
     */
    public function __construct(Translator_Interface $translator, private Theme_Context_Interface $theme_context)
    {
        foreach ([Locale_Aware_Interface::class, Translator_Bag_Interface::class] as $interface) {
            if (!$translator instanceof $interface) {
                throw new \InvalidArgumentException(sprintf('The translator "%s" must implement %s.', $translator::class, $interface));
            }
        }
        $this->translator = $translator;
    }
    /**
     * Passes through all unknown calls onto the translator object.
     */
    public function __call(string $method, array $arguments)
    {
        $translator = $this->translator;
        $arguments = array_values($arguments);
        return $translator->{$method}(...$arguments);
    }
    public function trans($id, array $parameters = [], $domain = null, ?string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $this->transform_locale($locale));
    }
    public function get_locale(): string
    {
        return $this->translator->get_locale();
    }
    /**
     * @param string $locale
     */
    public function set_locale($locale): void
    {
        /** @var string $locale */
        $locale = $this->transform_locale($locale);
        $this->translator->set_locale($locale);
    }
    /**
     * @param string|null $locale
     */
    public function get_catalogue($locale = null): Message_Catalogue_Interface
    {
        return $this->translator->get_catalogue($locale);
    }
    public function warm_up($cache_dir, ?string $build_dir = null): array
    {
        if ($this->translator instanceof Warmable_Interface) {
            return $this->translator->warm_up($cache_dir);
        }
        return [];
    }
    private function transform_locale(?string $locale): ?string
    {
        $theme = $this->theme_context->get_theme();
        if (null === $theme) {
            return $locale;
        }
        if (null === $locale) {
            $locale = $this->get_locale();
        }
        return $locale . '@' . str_replace('/', '-', $theme->get_name());
    }
    public function get_catalogues(): array
    {
        return $this->translator->get_catalogues();
    }
}