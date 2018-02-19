<?php

use HydrefLab\Laravel\ADR\Responder\ResponderFactory;
use HydrefLab\Laravel\ADR\Responder\ResponderInterface;
use Illuminate\Container\Container;

if (false === function_exists('get_caller_class')) {
    /**
     * Get class name of a caller object.
     *
     * @return null|string
     */
    function get_caller_class()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $class = $trace[1]['class'] ?? null;

        for ($i = 1; $i < count($trace); $i++) {
            if (true === isset($trace[$i]) && true === isset($trace[$i]['class']) && $class != $trace[$i]['class']) {
                return $trace[$i]['class'];
            }
        }

        return null;
    }
}

if (false === function_exists('responder')) {
    /**
     * Create new responder.
     *
     * @return ResponderInterface
     */
    function responder(): ResponderInterface
    {
        return ResponderFactory::create(Container::getInstance()->make('request'), ...func_get_args());
    }
}