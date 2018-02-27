<?php

namespace HydrefLab\Laravel\ADR\Tests;

use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionClassNameResponderClassNameResolver;
use HydrefLab\Laravel\ADR\Responder\ResponderInterface;
use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyClassB;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyResponderAction;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyResponderActionResponder;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testHelperCreatesResponder()
    {
        Container::getInstance()->instance('request', new Request());
        ResponderResolver::extend(new ByActionClassNameResponderClassNameResolver());

        /** @var ResponderInterface $responder */
        $responder = (new DummyResponderAction())();

        $this->assertInstanceOf(DummyResponderActionResponder::class, $responder);
        $this->assertEquals(['Dummy data'], $responder->respond());
    }

    /**
     * @test
     * @return void
     */
    public function testHelperGetsCallerClass()
    {
        $this->assertEquals(DummyClassB::class, (new DummyClassB())->getDummyClassBClassViaCallerClass());
    }
}