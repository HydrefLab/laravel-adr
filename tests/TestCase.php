<?php

namespace HydrefLab\Laravel\ADR\Tests;

use HydrefLab\Laravel\ADR\Action\ActionResolver;
use HydrefLab\Laravel\ADR\Action\Resolver\DefaultActionClassNameResolver;
use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionClassNameResponderClassNameResolver;
use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionPropertyResponderClassNameResolver;
use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    public function setUp()
    {
        config(['adr' => require __DIR__ . '/../config/config.php']);

        ActionResolver::extend(new DefaultActionClassNameResolver());

        ResponderResolver::extend(new ByActionClassNameResponderClassNameResolver());
        ResponderResolver::extend(new ByActionPropertyResponderClassNameResolver());
    }
}