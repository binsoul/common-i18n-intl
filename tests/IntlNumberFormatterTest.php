<?php

namespace BinSoul\Test\Common\I18n;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\IntlNumberFormatter;
use PHPUnit\Framework\TestCase;

class IntlNumberFormatterTest extends TestCase
{
    public function test_formats_decimal_numbers(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals('-1.001,12345', $formatter->formatDecimal(-1001.12345));
        $this->assertEquals('1.001,12345', $formatter->formatDecimal(1001.12345));
        $this->assertEquals('100.000.001,12345', $formatter->formatDecimal(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('en-US'));
        $this->assertEquals('-1,001.12345', $formatter->formatDecimal(-1001.12345));
        $this->assertEquals('1,001.12345', $formatter->formatDecimal(1001.12345));
        $this->assertEquals('100,000,001.12345', $formatter->formatDecimal(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));
        $this->assertEquals('؜-١٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(-1001.12345));
        $this->assertEquals('١٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(1001.12345));
        $this->assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ml'));
        $this->assertEquals('-1,001.12345', $formatter->formatDecimal(-1001.12345));
        $this->assertEquals('1,001.12345', $formatter->formatDecimal(1001.12345));
        $this->assertEquals('10,00,00,001.12345', $formatter->formatDecimal(100000001.12345));
    }

    public function test_formats_percents(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals('-1.001,12 %', $formatter->formatPercent(-1001.12345, 2));
        $this->assertEquals('-1.001,12345 %', $formatter->formatPercent(-1001.12345));
        $this->assertEquals('1.001,12345 %', $formatter->formatPercent(1001.12345));
        $this->assertEquals('100.000.001,12345 %', $formatter->formatPercent(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));

        $this->assertEquals('؜-١٬٠٠١٫١٢٣٤٥٪؜', $formatter->formatPercent(-1001.12345));
        $this->assertEquals('١٬٠٠١٫١٢٣٤٥٪؜', $formatter->formatPercent(1001.12345));
        $this->assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢٣٤٥٪؜', $formatter->formatPercent(100000001.12345));
    }

    public function test_formats_currency(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals('1.001,12', $formatter->formatCurrency(1001.12345));

        $this->assertEquals('-1.001,12 €', $formatter->formatCurrency(-1001.12345, 'EUR'));
        $this->assertEquals('1.001,12 €', $formatter->formatCurrency(1001.12345, 'EUR'));
        $this->assertEquals('100.000.001,12 €', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('fr-FR'));

        $this->assertEquals('-1 001,12 €', $formatter->formatCurrency(-1001.12345, 'EUR'));
        $this->assertEquals('1 001,12 €', $formatter->formatCurrency(1001.12345, 'EUR'));
        $this->assertEquals('100 000 001,12 €', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('en-US'));
        $this->assertEquals('-€1,001.12', $formatter->formatCurrency(-1001.12345, 'EUR'));
        $this->assertEquals('€1,001.12', $formatter->formatCurrency(1001.12345, 'EUR'));
        $this->assertEquals('€100,000,001.12', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));
        $this->assertEquals('؜-١٬٠٠١٫١٢ €', $formatter->formatCurrency(-1001.12345, 'EUR'));
        $this->assertEquals('١٬٠٠١٫١٢ €', $formatter->formatCurrency(1001.12345, 'EUR'));
        $this->assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢ €', $formatter->formatCurrency(100000001.12345, 'EUR'));
    }

    public function test_with_locale(): void
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('de-DE'));

        $this->assertSame($newFormatter, $formatter);

        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('en-US'));
        $this->assertEquals('$1,001.12', $newFormatter->formatCurrency(1001.12345, 'USD'));
    }
}
