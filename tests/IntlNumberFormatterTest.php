<?php

namespace BinSoul\Test\Common\I18n;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Intl\IntlNumberFormatter;
use PHPUnit\Framework\TestCase;

class IntlNumberFormatterTest extends TestCase
{
    /* NARROW NO BREAK SPACE */
    const NNBS = "\xE2\x80\xAF";

    /* NO BREAK SPACE */
    const NBS = "\xC2\xA0";

    /* RIGHT-TO-LEFT MARK */
    const RTLM = "\xE2\x80\x8F";

    /* ARABIC LETTER MARK */
    const ALM = "\xD8\x9C";

    public function test_formats_decimal_numbers()
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
        $this->assertEquals(self::ALM.'-١٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(-1001.12345));
        $this->assertEquals('١٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(1001.12345));
        $this->assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢٣٤٥', $formatter->formatDecimal(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ml'));
        $this->assertEquals('-1,001.12345', $formatter->formatDecimal(-1001.12345));
        $this->assertEquals('1,001.12345', $formatter->formatDecimal(1001.12345));
        $this->assertEquals('10,00,00,001.12345', $formatter->formatDecimal(100000001.12345));
    }

    public function test_formats_percents()
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals('-1.001,12'.self::NBS.'%', $formatter->formatPercent(-1001.12345, 2));
        $this->assertEquals('-1.001,12345'.self::NBS.'%', $formatter->formatPercent(-1001.12345));
        $this->assertEquals('1.001,12345'.self::NBS.'%', $formatter->formatPercent(1001.12345));
        $this->assertEquals('100.000.001,12345'.self::NBS.'%', $formatter->formatPercent(100000001.12345));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('ar-EG'));

        $this->assertEquals(self::ALM.'-١٬٠٠١٫١٢٣٤٥٪'.self::ALM, $formatter->formatPercent(-1001.12345));
        $this->assertEquals('١٬٠٠١٫١٢٣٤٥٪'.self::ALM, $formatter->formatPercent(1001.12345));
        $this->assertEquals('١٠٠٬٠٠٠٬٠٠١٫١٢٣٤٥٪'.self::ALM, $formatter->formatPercent(100000001.12345));
    }

    public function test_formats_currency()
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $this->assertEquals('1.001,12', $formatter->formatCurrency(1001.12345));

        $this->assertEquals('-1.001,12'.self::NBS.'€', $formatter->formatCurrency(-1001.12345, 'EUR'));
        $this->assertEquals('1.001,12'.self::NBS.'€', $formatter->formatCurrency(1001.12345, 'EUR'));
        $this->assertEquals('100.000.001,12'.self::NBS.'€', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('fr-FR'));

        $this->assertEquals('-1'.self::NNBS.'001,12'.self::NBS.'€', $formatter->formatCurrency(-1001.12345, 'EUR'));
        $this->assertEquals('1'.self::NNBS.'001,12'.self::NBS.'€', $formatter->formatCurrency(1001.12345, 'EUR'));
        $this->assertEquals('100'.self::NNBS.'000'.self::NNBS.'001,12'.self::NBS.'€', $formatter->formatCurrency(100000001.12345, 'EUR'));

        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('en-US'));
        $this->assertEquals('-€1,001.12', $formatter->formatCurrency(-1001.12345, 'EUR'));
        $this->assertEquals('€1,001.12', $formatter->formatCurrency(1001.12345, 'EUR'));
        $this->assertEquals('€100,000,001.12', $formatter->formatCurrency(100000001.12345, 'EUR'));
    }

    public function test_with_locale()
    {
        $formatter = new IntlNumberFormatter(DefaultLocale::fromString('de-DE'));
        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('de-DE'));

        $this->assertSame($newFormatter, $formatter);

        $newFormatter = $formatter->withLocale(DefaultLocale::fromString('en-US'));
        $this->assertEquals('$1,001.12', $newFormatter->formatCurrency(1001.12345, 'USD'));
    }
}
