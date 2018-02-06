<?php

namespace HydrefLab\Laravel\ADR\Responder\Resolver;

class ByAttributeResponderResolver
{
    /**
     * @param string $actionClassName
     * @return null|string
     */
    public function __invoke(string $actionClassName)
    {
        return (new \ReflectionClass($actionClassName))->getDefaultProperties()['responderClass'] ?? null;
    }
}