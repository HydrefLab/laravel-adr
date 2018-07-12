<?php

namespace HydrefLab\Laravel\ADR\Action;

class ActionResolver
{
    /**
     * Registered resolvers.
     *
     * @var array
     */
    protected static $resolvers = [];

    /**
     * Resolve action class name.
     *
     * @param string $namespace
     * @param string $resource
     * @param string $actionType
     * @return string|null
     */
    public static function resolveClassName(string $namespace, string $resource, string $actionType)
    {
        foreach (array_reverse(static::$resolvers) as $resolver) {
            $actionClassName = $resolver($namespace, $resource, $actionType);

            if (!is_null($actionClassName)) {
                return $actionClassName;
            }
        }

        return null;
    }

    /**
     * Register resolver.
     *
     * @param callable $resolver
     * @return void
     */
    public static function extend(callable $resolver)
    {
        static::$resolvers[] = $resolver;
    }
}