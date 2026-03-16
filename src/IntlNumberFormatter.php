<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl;

use BinSoul\Common\I18n\DefaultLocale;
use BinSoul\Common\I18n\Locale;
use BinSoul\Common\I18n\NumberFormatter;

/**
 * Formats numbers using the NumberFormatter class of the PHP intl extension.
 */
class IntlNumberFormatter implements NumberFormatter
{
    private readonly Locale $locale;

    private ?\NumberFormatter $decimalFormatter = null;

    private ?\NumberFormatter $percentFormatter = null;

    private ?\NumberFormatter $currencyFormatter = null;

    /**
     * Constructs an instance of this class.
     */
    public function __construct(?Locale $locale = null)
    {
        $this->locale = $locale ?? DefaultLocale::fromString(\Locale::getDefault());
    }

    public function formatDecimal(float $value, ?int $decimals = null): string
    {
        $formatter = $this->getDecimalFormatter();

        if ($decimals !== null) {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
        } else {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 100);
        }

        $result = $formatter->format($value);

        return $result !== false ? $result : '';
    }

    public function formatPercent(float $value, ?int $decimals = null): string
    {
        $formatter = $this->getPercentFormatter();

        if ($decimals !== null) {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);
        } else {
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 100);
        }

        $result = $formatter->format($value / 100);

        return $result !== false ? $result : '';
    }

    public function formatCurrency(float $value, string $currencyCode = ''): string
    {
        $formatter = $this->getCurrencyFormatter();

        if ($currencyCode === '') {
            return $this->formatDecimal($value, $formatter->getAttribute(\NumberFormatter::FRACTION_DIGITS));
        }

        $result = $formatter->formatCurrency($value, $currencyCode);

        return $result !== false ? $result : '';
    }

    public function getPercentSymbol(): string
    {
        return $this->getPercentFormatter()->getSymbol(\NumberFormatter::PERCENT_SYMBOL);
    }

    public function getCurrencySymbol(string $currencyCode): string
    {
        $formatter = new \NumberFormatter($this->locale->getCode('_') . '@currency=' . $currencyCode, \NumberFormatter::CURRENCY);

        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }

    public function withLocale(Locale $locale): NumberFormatter
    {
        if ($locale->getCode() === $this->locale->getCode()) {
            return $this;
        }

        return new self($locale);
    }

    /**
     * Returns a decimal number formatter.
     */
    private function getDecimalFormatter(): \NumberFormatter
    {
        if (! is_object($this->decimalFormatter)) {
            $this->decimalFormatter = new \NumberFormatter($this->locale->getCode('_'), \NumberFormatter::DECIMAL);
        }

        return $this->decimalFormatter;
    }

    /**
     * Returns a percent formatter.
     */
    private function getPercentFormatter(): \NumberFormatter
    {
        if (! is_object($this->percentFormatter)) {
            $this->percentFormatter = new \NumberFormatter($this->locale->getCode('_'), \NumberFormatter::PERCENT);
        }

        return $this->percentFormatter;
    }

    /**
     * Returns a currency formatter.
     */
    private function getCurrencyFormatter(): \NumberFormatter
    {
        if (! is_object($this->currencyFormatter)) {
            $this->currencyFormatter = new \NumberFormatter($this->locale->getCode('_'), \NumberFormatter::CURRENCY);
        }

        return $this->currencyFormatter;
    }
}
