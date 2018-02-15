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
        try {
            return (new \ReflectionClass($actionClassName))->getDefaultProperties()['responderClass'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}