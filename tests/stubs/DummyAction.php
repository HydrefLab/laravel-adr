<?php

namespace HydrefLab\Laravel\ADR\Tests\stubs;

class DummyAction
{
    /**
     * @var string
     */
    protected $responderClass = DummyActionResponder::class;

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return [];
    }
}