<?php

namespace HydrefLab\Laravel\ADR\Responder;

use Illuminate\Http\Request;

class ResponderFactory
{
    /**
     * Create new responder.
     *
     * @param Request $request
     * @param array ...$args
     * @return ResponderInterface
     */
    public static function create(Request $request, ...$args): ResponderInterface
    {
        return ResponderResolver::resolve(get_caller_class(), $request, ...$args);
    }
}