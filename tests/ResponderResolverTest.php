<?php

namespace HydrefLab\Laravel\ADR\Tests;

use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionClassNameResponderResolver;
use HydrefLab\Laravel\ADR\Responder\Resolver\ByAttributeResponderResolver;
use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyAction;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyActionResponder;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyEmptyAction;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class ResponderResolverTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp()
    {
        ResponderResolver::extend(new ByActionClassNameResponderResolver());
        ResponderResolver::extend(new ByAttributeResponderResolver());
    }

    /**
     * @return void
     */
    public function testResolveResponder()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();
        $responder = ResponderResolver::resolve(DummyAction::class, $request, 'Dummy data', 500);

        $this->assertInstanceOf(DummyActionResponder::class, $responder);
        $this->assertEquals(['Dummy data', 500], $responder->respond());
    }

    /**
     * @return void
     */
    public function testResolveResponderWithoutResponderInterface()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();
        $responderClassName = ResponderResolver::resolveClassName(DummyEmptyAction::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Responder {$responderClassName} must implement ResponderInterface interface");

        ResponderResolver::resolve(DummyEmptyAction::class, $request, ['Dummy data']);
    }

    /**
     * @return void
     */
    public function testResolveResponderForNonExistingAction()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find responder for action Non\Existing\Action');

        ResponderResolver::resolve('Non\Existing\Action', $request, ['Dummy data']);
    }
}