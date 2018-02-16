<?php

namespace HydrefLab\Laravel\ADR\Tests\stubs;

class DummyClassA
{
    /**
     * @return string
     */
    public function getCallerClass(): string
    {
        return get_caller_class();
    }
}