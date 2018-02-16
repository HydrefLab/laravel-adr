<?php

namespace HydrefLab\Laravel\ADR\Tests\stubs;

use HydrefLab\Laravel\ADR\Responder\ResponderInterface;

class DummyResponderAction
{
    /**
     * @return \HydrefLab\Laravel\ADR\Responder\ResponderInterface
     */
    public function __invoke(): ResponderInterface
    {
        return responder(['Dummy data']);
    }
}