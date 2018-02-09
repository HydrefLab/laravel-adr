<?php

namespace HydrefLab\Laravel\ADR\Responder;

use Illuminate\Http\Request;

class ResponderResolver
{
    /**
     * @var array
     */
    protected static $resolvers = [];

    /**
     * @param string $actionClassName
     * @param Request $request
     * @param array ...$response
     * @return ResponderInterface
     */
    public static function resolve(string $actionClassName, Request $request, ...$response): ResponderInterface
    {
        $responderClassName = static::resolveClassName($actionClassName);

        if (false === is_null($responderClassName) && true === class_exists($responderClassName)) {
            $responder = new $responderClassName($request, ...$response);

            if (false === $responder instanceof ResponderInterface) {
                throw new \InvalidArgumentException("Responder $responderClassName must implement ResponderInterface interface");
            }

            return $responder;
        }

        throw new \InvalidArgumentException("Could not find responder for action $actionClassName");
    }

    /**
     * @param string $actionClassName
     * @return null|string
     */
    public static function resolveClassName(string $actionClassName)
    {
        foreach (array_reverse(static::$resolvers) as $resolver) {
            $responderClassName = $resolver($actionClassName);

            if (false === is_null($responderClassName)) {
                return $responderClassName;
            }
        }

        return null;
    }

    /**
     * @param callable $resolver
     * @return void
     */
    public static function extend(callable $resolver)
    {
        static::$resolvers[] = $resolver;
    }
}