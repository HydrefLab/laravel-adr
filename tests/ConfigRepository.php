<?php

namespace HydrefLab\Laravel\ADR\Tests;

use Illuminate\Support\Arr;

class ConfigRepository
{
    /**
     * @var array
     */
    public static $items = [];

    /**
     * @param array|string  $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (is_array($key)) {
            return static::getMany($key);
        }

        return Arr::get(static::$items, $key, $default);
    }
    /**
     *
     * @param array $keys
     * @return array
     */
    public static function getMany($keys)
    {
        $config = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                list($key, $default) = [$default, null];
            }

            $config[$key] = Arr::get(static::$items, $key, $default);
        }

        return $config;
    }

    /**
     * @param array|string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set(static::$items, $key, $value);
        }
    }
}