<?php

namespace HydrefLab\Laravel\ADR\Tests;

use HydrefLab\Laravel\ADR\Responder\Resolver\ByAttributeResponderResolver;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyAction;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyActionResponder;
use HydrefLab\Laravel\ADR\Tests\stubs\DummyEmptyAction;
use PHPUnit\Framework\TestCase;

class ByAttributeResponderResolverTest extends TestCase
{
    /**
     * @return void
     */
    public function testResolveResponderClassNameForActionWithoutResponderClass()
    {
        $this->assertNull((new ByAttributeResponderResolver())(DummyEmptyAction::class));
    }

    /**
     * @return void
     */
    public function testResolveResponderClassNameForActionWithResponderClass()
    {
        $this->assertEquals(DummyActionResponder::class, (new ByAttributeResponderResolver())(DummyAction::class));
    }

    /**
     * @return void
     */
    public function testResolveResponderClassNameForNonExistingAction()
    {
        $this->assertNull((new ByAttributeResponderResolver())('Non\Existing\Action'));
    }
}