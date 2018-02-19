<?php

namespace HydrefLab\Laravel\ADR\Action;

use Illuminate\Support\Str;

class ActionResolver
{
    /**
     * Resolve action class name.
     *
     * @param string $namespace
     * @param string $resource
     * @param string $actionType
     * @return string
     */
    public static function resolveClassName(string $namespace, string $resource, string $actionType): string
    {
        $resource = ('index' !== Str::lower($actionType))
            ? Str::singular($resource)
            : Str::plural($resource);

        $actionClassName = sprintf(
            '%s\%s%sAction',
            $namespace,
            ucfirst(Str::lower($actionType)),
            ucfirst(Str::lower($resource))
        );

        return trim($actionClassName, '\\');
    }
}