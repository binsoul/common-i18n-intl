<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl;

use BinSoul\Common\I18n\DefaultAddressFormatter;
use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Locale;

/**
 * Formats addresses using the PHP intl extension.
 */
class IntlAddressFormatter extends DefaultAddressFormatter
{
    public function __construct(?Locale $locale = null)
    {
        parent::__construct($locale ?? DefaultLocale::fromString(\Locale::getDefault()));
    }

    protected function isoCodeToName(string $isoCode): string
    {
        return \Locale::getDisplayRegion('-' . $isoCode, $this->locale->getCode());
    }
}
