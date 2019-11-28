<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\DefaultSlugGenerator;
use BinSoul\Common\I18n\Locale;

/**
 * Generates slugs using the PHP intl extension.
 */
class IntlSlugGenerator extends DefaultSlugGenerator
{
    public function __construct(?Locale $locale = null)
    {
        parent::__construct($locale ?? DefaultLocale::fromString(\Locale::getDefault()));
    }
}
