<?php

namespace ACSEO\SyliusAITools\Twig;

use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocaleExtension extends AbstractExtension
{
    public function __construct(private LocaleProviderInterface $localeProvider)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_all_locales_codes', [$this, 'getAllLocalesCodes']),
        ];
    }

    public function getAllLocalesCodes(): array
    {
        return $this->localeProvider->getAvailableLocalesCodes();
    }
}
