<?php

namespace HydrefLab\Laravel\ADR\Action\Resolver;

use Illuminate\Support\Str;

class DefaultActionClassNameResolver
{
    /**
     * Default action class name resolver.
     *
     * @param string $namespace
     * @param string $resource
     * @param string $actionType
     * @return string
     */
    public function __invoke(string $namespace, string $resource, string $actionType): string
    {
        $resource = ('index' !== Str::lower($actionType))
            ? Str::singular($resource)
            : Str::plural($resource);

        $actionClassName = sprintf(
            '%s\%s%s%s',
            $namespace,
            ucfirst(Str::lower($actionType)),
            ucfirst(Str::lower($resource)),
            config('adr.postfix.actions', '')
        );

        return trim($actionClassName, '\\');
    }
}