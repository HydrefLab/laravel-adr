<?php

namespace HydrefLab\Laravel\ADR\Tests\stubs;

class DummyEmptyAction
{
    /**
     * @return mixed
     */
    public function __invoke()
    {
        return [];
    }
}