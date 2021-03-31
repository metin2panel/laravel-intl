<?php

namespace Depiedra\LaravelIntl;

use CommerceGuys\Intl\Formatter\NumberFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use Depiedra\LaravelIntl\Concerns\WithLocales;
use Depiedra\LaravelIntl\Contracts\Intl;
use Illuminate\Support\Arr;

class Number extends Intl
{
    use WithLocales;

    /**
     * Array of localized number formatters.
     *
     * @var array
     */
    protected $formatters;

    /**
     * The default options.
     *
     * @var array
     */
    protected $defaultOptions = [
        'minimum_fraction_digits' => 0,
        'maximum_fraction_digits' => 2,
        'rounding_mode' => PHP_ROUND_HALF_UP,
        'style' => 'decimal',
    ];

    /**
     * Format a number.
     *
     * @param string|int|float $number
     * @param array $options
     *
     * @return string
     */
    public function format($number, $options = [])
    {
        return $this->formatter()->format($number,
            $this->mergeOptions($options)
        );
    }

    /**
     * Format as percentage.
     *
     * @param string|int|float $number
     * @param array $options
     *
     * @return string
     */
    public function percent($number, $options = [])
    {
        return $this->formatter()->format($number,
            $this->mergeOptions($options, ['style' => 'percent'])
        );
    }

    /**
     * Parse a localized number into native PHP format.
     *
     * @param string|int|float $number
     * @param array $options
     *
     * @return string|false
     */
    public function parse($number, $options = [])
    {
        return $this->formatter()->parse($number,
            $this->mergeOptions($options)
        );
    }

    /**
     * Get the formatter's key.
     *
     * @param string $locale
     * @param string $fallbackLocale
     *
     * @return string
     */
    protected function getLocalesKey($locale, $fallbackLocale)
    {
        return implode('|', [
            $locale,
            $fallbackLocale,
        ]);
    }

    /**
     * The current number formatter.
     *
     * @return \CommerceGuys\Intl\Formatter\NumberFormatter
     */
    protected function formatter()
    {
        $key = $this->getLocalesKey(
            $locale = $this->getLocale(),
            $fallbackLocale = $this->getFallbackLocale()
        );

        if (! isset($this->formatters[$key])) {
            $defaultOptions = array_merge($this->defaultOptions, [
                'locale' => $locale,
            ]);

            $this->formatters[$key] = new NumberFormatter(
                new NumberFormatRepository($fallbackLocale),
                $defaultOptions
            );
        }

        return $this->formatters[$key];
    }

    /**
     * Merges the options array.
     *
     * @param array $options
     * @param array $defaults
     *
     * @return array
     */
    protected function mergeOptions(array $options, array $defaults = [])
    {
        Arr::forget($options, 'locale');

        return $defaults + $options;
    }
}