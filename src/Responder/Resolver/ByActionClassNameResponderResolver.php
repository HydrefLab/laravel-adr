<?php

namespace HydrefLab\Laravel\ADR\Responder\Resolver;

class ByActionClassNameResponderResolver
{
    /**
     * @param string $actionClassName
     * @return string
     */
    public function __invoke(string $actionClassName): string
    {
        $actionClassName = explode('\\', $actionClassName);

        $responderClassName = sprintf(
            '%s\\%s',
            str_replace('Actions', 'Responders', implode('\\', array_slice($actionClassName, 0, -1))),
            last($actionClassName) . 'Responder'
        );

        return trim($responderClassName, '\\');
    }
}