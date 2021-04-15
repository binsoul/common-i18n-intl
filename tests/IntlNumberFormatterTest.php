<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\IntlNumberFormatter;
use Locale;
use PHPUnit\Framework\TestCase;

class IntlNumberFormatterTest extends TestCase
{
    public function test_formats_decimal_numbers(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals('-1.001,12345', $formatter->formatDecimal(-1001.12345));
        self::assertEquals('1.001,12345', $formatter->formatDecimal(1001.12345));
        self::assertEquals('100.000.001,12345', $formatter->formatDecimal(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('en-US'));
        self::assertEquals('-1,001.12345', $formatter->formatDecimal(-1001.12345));
        self::assertEquals('1,001.12345', $formatter->formatDecimal(1001.12345));
        self::assertEquals('100,000,001.12345', $formatter->formatDecimal(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));
        self::assertEquals('؜-١٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(-1001.12345));
        self::assertEquals('١٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(1001.12345));
        self::assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ml'));
        self::assertEquals('-1,001.12345', $formatter->formatDecimal(-1001.12345));
        self::assertEquals('1,001.12345', $formatter->formatDecimal(1001.12345));
        self::assertEquals('10,00,00,001.12345', $formatter->formatDecimal(100000001.12345));
    }

    public function test_formats_percents(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals('-1.001,12 %', $formatter->formatPercent(-1001.12345, 2));
        self::assertEquals('-1.001,12345 %', $formatter->formatPercent(-1001.12345));
        self::assertEquals('1.001,12345 %', $formatter->formatPercent(1001.12345));
        self::assertEquals('100.000.001,12345 %', $formatter->formatPercent(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));

        self::assertEquals('؜-١٬٠٠١٫١٢٣٤٥٪؜', $formatter->formatPercent(-1001.12345));
        self::assertEquals('١٬٠٠١٫١٢٣٤٥٪؜', $formatter->formatPercent(1001.12345));
        self::assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢٣٤٥٪؜', $formatter->formatPercent(100000001.12345));
    }

    public function test_formats_currency(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals('1.001,12', $formatter->formatCurrency(1001.12345));

        self::assertEquals('-1.001,12 €', $formatter->formatCurrency(-1001.12345, 'EUR'));
        self::assertEquals('1.001,12 €', $formatter->formatCurrency(1001.12345, 'EUR'));
        self::assertEquals('100.000.001,12 €', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('fr-FR'));

        self::assertEquals('-1 001,12 €', $formatter->formatCurrency(-1001.12345, 'EUR'));
        self::assertEquals('1 001,12 €', $formatter->formatCurrency(1001.12345, 'EUR'));
        self::assertEquals('100 000 001,12 €', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('en-US'));
        self::assertEquals('-€1,001.12', $formatter->formatCurrency(-1001.12345, 'EUR'));
        self::assertEquals('€1,001.12', $formatter->formatCurrency(1001.12345, 'EUR'));
        self::assertEquals('€100,000,001.12', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));
        self::assertEquals('؜-١٬٠٠١٫١٢ €', $formatter->formatCurrency(-1001.12345, 'EUR'));
        self::assertEquals('١٬٠٠١٫١٢ €', $formatter->formatCurrency(1001.12345, 'EUR'));
        self::assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢ €', $formatter->formatCurrency(100000001.12345, 'EUR'));
    }

    public function test_returns_percent_symbol(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals('%', $formatter->getPercentSymbol());
        self::assertEquals($formatter->formatDecimal(-1001.12345) . ' ', str_replace($formatter->getPercentSymbol(), '', $formatter->formatPercent(-1001.12345)));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));
        self::assertEquals('٪؜', $formatter->getPercentSymbol());
        self::assertEquals($formatter->formatDecimal(-1001.12345), str_replace($formatter->getPercentSymbol(), '', $formatter->formatPercent(-1001.12345)));
    }

    public function test_returns_currency_symbol(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        self::assertEquals('€', $formatter->getCurrencySymbol('EUR'));
        self::assertEquals('$', $formatter->getCurrencySymbol('USD'));
        self::assertEquals('CA$', $formatter->getCurrencySymbol('CAD'));

        self::assertEquals($formatter->formatCurrency(-1001.12345) . ' ', str_replace($formatter->getCurrencySymbol('EUR'), '', $formatter->formatCurrency(-1001.12345, 'EUR')));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('en-CA'));
        self::assertEquals('€', $formatter->getCurrencySymbol('EUR'));
        self::assertEquals('US$', $formatter->getCurrencySymbol('USD'));
        self::assertEquals('$', $formatter->getCurrencySymbol('CAD'));

        self::assertEquals($formatter->formatCurrency(-1001.12345), str_replace($formatter->getCurrencySymbol('EUR'), '', $formatter->formatCurrency(-1001.12345, 'EUR')));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));
        self::assertEquals('€', $formatter->getCurrencySymbol('EUR'));
        self::assertEquals('US$', $formatter->getCurrencySymbol('USD'));
        self::assertEquals('CA$', $formatter->getCurrencySymbol('CAD'));

        self::assertEquals($formatter->formatCurrency(-1001.12345) . ' ', str_replace($formatter->getCurrencySymbol('EUR'), '', $formatter->formatCurrency(-1001.12345, 'EUR')));
    }

    public function test_with_locale(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('de-DE'));

        self::assertSame($newFormatter, $formatter);

        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('en-US'));
        self::assertEquals('$1,001.12', $newFormatter->formatCurrency(1001.12345, 'USD'));
    }

    public function test_uses_default_locale(): void
    {
        $formatter1 = new IntlNumberFormatter();
        $formatter2 = new IntlNumberFormatter(DefaultLocale::fromString(Locale::getDefault()));
        self::assertEquals($formatter2->formatCurrency(1001.12345), $formatter1->formatCurrency(1001.12345));
    }
}
