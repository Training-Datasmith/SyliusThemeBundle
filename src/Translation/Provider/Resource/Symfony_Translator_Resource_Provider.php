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
namespace Sylius\Bundle\Theme_Bundle\Translation\Provider\Resource;

use Sylius\Bundle\Theme_Bundle\Translation\Resource\Translation_Resource;
use Sylius\Bundle\Theme_Bundle\Translation\Resource\Translation_Resource_Interface;
final class Symfony_Translator_Resource_Provider implements Translator_Resource_Provider_Interface
{
    /** @var TranslationResourceInterface[] */
    private array $resources = [];
    private array $resources_locales = [];
    public function __construct(private array $filepaths = [])
    {
    }
    public function get_resources(): array
    {
        $this->initialize_if_needed();
        return $this->resources;
    }
    public function get_resources_locales(): array
    {
        $this->initialize_if_needed();
        return $this->resources_locales;
    }
    private function initialize_if_needed(): void
    {
        foreach ($this->filepaths as $filepath) {
            $resource = new Translation_Resource($filepath);
            $this->resources[] = $resource;
            $this->resources_locales[] = $resource->get_locale();
        }
        $this->resources_locales = array_unique($this->resources_locales);
        $this->filepaths = [];
    }
}