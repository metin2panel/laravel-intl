<?php

use Jenssegers\Date\Date;

if (! function_exists('country')) {
    /**
     * Get a localized country name.
     *
     * @param string|null $countryCode
     *
     * @return \Depiedra\LaravelIntl\Country|string
     */
    function country($countryCode = null)
    {
        if (is_null($countryCode)) {
            return app('intl.country');
        }

        return app('intl.country')->name($countryCode);
    }
}

if (! function_exists('currency')) {
    /**
     * Get a localized currency or currency amount.
     *
     * @return \Depiedra\LaravelIntl\Currency|string
     */
    function currency()
    {
        $arguments = func_get_args();

        if (! count($arguments)) {
            return app('intl.currency');
        }

        $result = is_numeric($arguments[0])
            ? app('intl.currency')->format(...$arguments)
            : app('intl.currency')->parse(...$arguments);

        return $result !== false ? $result : $arguments[0];
    }
}

if (! function_exists('carbon')) {
    /**
     * Get a localized Carbon instance.
     *
     * @param  string $time
     * @param  string|DateTimeZone $timezone
     *
     * @return \Jenssegers\Date\Date|string
     */
    function carbon($time = null, $timezone = null)
    {
        return Date::make($time, $timezone);
    }
}

if (! function_exists('language')) {
    /**
     * Get a localized language name.
     *
     * @param string|null $langCode
     *
     * @return \Depiedra\LaravelIntl\Language|string
     */
    function language($langCode = null)
    {
        if (is_null($langCode)) {
            return app('intl.language');
        }

        return app('intl.language')->name($langCode);
    }
}

if (! function_exists('number')) {
    /**
     * Get a formatted localized number.
     *
     * @return \Depiedra\LaravelIntl\Number|string
     */
    function number()
    {
        $arguments = func_get_args();

        if (! count($arguments)) {
            return app('intl.number');
        }

        $result = is_numeric($arguments[0])
            ? app('intl.number')->format(...$arguments)
            : app('intl.number')->parse(...$arguments);

        return $result !== false ? $result : $arguments[0];
    }
}
