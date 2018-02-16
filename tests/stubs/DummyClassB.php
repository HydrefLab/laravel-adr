<?php

namespace HydrefLab\Laravel\ADR\Tests\stubs;

class DummyClassB
{
    /**
     * @return string
     */
    public function getDummyClassBClassViaCallerClass(): string
    {
        return (new DummyClassA())->getCallerClass();
    }
}