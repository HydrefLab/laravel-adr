<?php

namespace HydrefLab\Laravel\ADR\Responder\Resolver;

class ByActionPropertyResponderClassNameResolver
{
    /**
     * Resolve responder class name by action property.
     *
     * 'responderClass' property from action class is used to resolve responder class name.
     *
     * @param string $actionClassName
     * @return null|string
     */
    public function __invoke(string $actionClassName)
    {
        try {
            return (new \ReflectionClass($actionClassName))->getDefaultProperties()['responderClass'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}