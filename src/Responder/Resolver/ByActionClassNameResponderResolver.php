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
        $actionClassReflection = new \ReflectionClass($actionClassName);

        return sprintf(
            '%s\\%s',
            str_replace('Actions', 'Responders', $actionClassReflection->getNamespaceName()),
            $actionClassReflection->getShortName() . 'Responder'
        );
    }
}