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
     * @param mixed $response
     * @return ResponderInterface
     * @throws \Exception
     */
    public static function resolve(string $actionClassName, Request $request, $response): ResponderInterface
    {
        foreach (array_reverse(static::$resolvers) as $resolver) {
            $responderClassName = $resolver($actionClassName);

            if (false === is_null($responderClassName) && true === class_exists($responderClassName)) {
                $responder = new $responderClassName($request, $response);

                if (false === $responder instanceof ResponderInterface) {
                    throw new \Exception();
                }

                return $responder;
            }
        }

        throw new \Exception();
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