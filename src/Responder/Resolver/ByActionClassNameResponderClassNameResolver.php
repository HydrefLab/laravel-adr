<?php

namespace HydrefLab\Laravel\ADR\Responder\Resolver;

class ByActionClassNameResponderClassNameResolver
{
    /**
     * Resolve responder class name by action class name.
     *
     * @param string $actionClassName
     * @return string
     */
    public function __invoke(string $actionClassName): string
    {
        $actionClassName = explode('\\', $actionClassName);

        $responderClassName = sprintf(
            '%s\\%s',
            str_replace(
                config('adr.namespace.actions', ''),
                config('adr.namespace.responders', ''),
                implode('\\', array_slice($actionClassName, 0, -1))
            ),
            last($actionClassName) . config('adr.postfix.responders')
        );

        return trim($responderClassName, '\\');
    }
}