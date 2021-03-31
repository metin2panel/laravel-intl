<?php

namespace Depiedra\LaravelIntl;

use Depiedra\LaravelIntl\Concerns\WithLocales;
use Depiedra\LaravelIntl\Contracts\Intl;
use Illuminate\Support\Arr;

class Language extends Intl
{
    use WithLocales;

    /**
     * Loaded localized country data.
     *
     * @var array
     */
    protected $data;

    /**
     * Get a localized record by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return Arr::get($this->all(), $key);
    }

    /**
     * Alias of get().
     *
     * @param string $key
     *
     * @return string
     */
    public function name($key)
    {
        return $this->get($key);
    }

    /**
     * Get all localized records.
     *
     * @return array
     */
    public function all()
    {
        $default = $this->data($this->getLocale());
        $fallback = $this->data($this->getFallbackLocale());

        return $default + $fallback;
    }

    /**
     * Load the data for the given locale.
     *
     * @param string $locale
     *
     * @return array
     */
    protected function data($locale)
    {
        if (! isset($this->data[$locale])) {
            $path = base_path('vendor/umpirsky/locale-list/data/' . $locale . '/locales.php');

            $this->data[$locale] = is_file($path) ? require $path : [];
        }

        return $this->data[$locale];
    }
}