<?php

namespace HydrefLab\Laravel\ADR\Tests;

use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionPropertyResponderClassNameResolver;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyAction;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyActionResponder;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyEmptyAction;
use PHPUnit\Framework\TestCase;

class ByActionPropertyResponderClassNameResolverTest extends TestCase
{
    /**
     * @return void
     */
    public function testResolveResponderClassNameForActionWithoutResponderClass()
    {
        $this->assertNull((new ByActionPropertyResponderClassNameResolver())(DummyEmptyAction::class));
    }

    /**
     * @return void
     */
    public function testResolveResponderClassNameForActionWithResponderClass()
    {
        $this->assertEquals(DummyActionResponder::class, (new ByActionPropertyResponderClassNameResolver())(DummyAction::class));
    }

    /**
     * @return void
     */
    public function testResolveResponderClassNameForNonExistingAction()
    {
        $this->assertNull((new ByActionPropertyResponderClassNameResolver())('Non\Existing\Action'));
    }
}