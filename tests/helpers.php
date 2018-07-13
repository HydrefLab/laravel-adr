<?php

if (!function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return \HydrefLab\Laravel\ADR\Tests\ConfigRepository::$items;
        }

        if (is_array($key)) {
            return \HydrefLab\Laravel\ADR\Tests\ConfigRepository::set($key);
        }

        return \HydrefLab\Laravel\ADR\Tests\ConfigRepository::get($key, $default);
    }
}