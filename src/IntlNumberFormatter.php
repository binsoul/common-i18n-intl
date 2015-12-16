<?php

namespace BinSoul\Common\I18n\Intl;

use BinSoul\Common\I18n\Locale;
use BinSoul\Common\I18n\NumberFormatter;

/**
 * Formats numbers using the NumberFormatter class of the PHP intl extension.
 */
class IntlNumberFormatter implements NumberFormatter
{
    /** @var Locale */
    private $locale;
    /** @var \NumberFormatter */
    private $decimalFormatter;
    /** @var \NumberFormatter */
    private $percentFormatter;
    /** @var \NumberFormatter */
    private $currencyFormatter;

    /**
     * Constructs an instance of this class.
     *
     * @param Locale $locale
     */
    public function __construct(Locale $locale)
    {
        $this->locale = $locale;
    }

    public function formatDecimal($value, $decimals = null)
    {
        if (!is_numeric($value)) {
            return '';
        }

        $formatter = $this->getDecimalFormatter();
        if ($decimals !== null) {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
        } else {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 100);
        }

        return $formatter->format($value);
    }

    public function formatPercent($value, $decimals = null)
    {
        if (!is_numeric($value)) {
            return '';
        }

        $formatter = $this->getPercentFormatter();
        if ($decimals !== null) {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
        } else {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 100);
        }

        return $formatter->format($value / 100);
    }

    public function formatCurrency($value, $currencyCode = '')
    {
        if (!is_numeric($value)) {
            return '';
        }

        $formatter = $this->getCurrencyFormatter();
        if ($currencyCode == '') {
            return $this->formatDecimal($value, $formatter->getAttribute(\NumberFormatter::FRACTION_DIGITS));
        }

        return $formatter->formatCurrency($value, $currencyCode);
    }

    public function withLocale(Locale $locale)
    {
        if ($locale->getCode() == $this->locale->getCode()) {
            return $this;
        }

        return new self($locale);
    }

    /**
     * Returns a decimal number formatter.
     *
     * @return \NumberFormatter
     */
    private function getDecimalFormatter()
    {
        if (!is_object($this->decimalFormatter)) {
            $this->decimalFormatter = new \NumberFormatter($this->locale->getCode('_'), \NumberFormatter::DECIMAL);
        }

        return $this->decimalFormatter;
    }

    /**
     * Returns a percent formatter.
     *
     * @return \NumberFormatter
     */
    private function getPercentFormatter()
    {
        if (!is_object($this->percentFormatter)) {
            $this->percentFormatter = new \NumberFormatter($this->locale->getCode('_'), \NumberFormatter::PERCENT);
        }

        return $this->percentFormatter;
    }

    /**
     * Returns a currency formatter.
     *
     * @return \NumberFormatter
     */
    private function getCurrencyFormatter()
    {
        if (!is_object($this->currencyFormatter)) {
            $this->currencyFormatter = new \NumberFormatter($this->locale->getCode('_'), \NumberFormatter::CURRENCY);
        }

        return $this->currencyFormatter;
    }
}
