<?php

namespace HydrefLab\Laravel\ADR\Tests\Unit;

use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionClassNameResponderClassNameResolver;
use HydrefLab\Laravel\ADR\Tests\TestCase;

class ByActionClassNameResponderClassNameResolverTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        config(['adr.namespace.actions' => 'DummyNamespace\Actions']);
        config(['adr.namespace.responders' => 'DummyNamespace\Responders']);
    }

    /**
     * @return array
     */
    public function actionClassNameProvider()
    {
        return [
            ['DummyAction', 'DummyActionResponder'],
            ['DummyNamespace\DummyAction', 'DummyNamespace\DummyActionResponder'],
            ['DummyNamespace\Actions\DummyAction', 'DummyNamespace\Responders\DummyActionResponder'],
            ['DummyNamespace\Actions\DummyModule\DummyAction', 'DummyNamespace\Responders\DummyModule\DummyActionResponder'],
            ['\DummyNamespace\DummyAction', 'DummyNamespace\DummyActionResponder'],
        ];
    }

    /**
     * @dataProvider actionClassNameProvider
     * @param string $actionClassName
     * @param string $expected
     * @return void
     */
    public function testResolveResponderClassName(string $actionClassName, string $expected)
    {
        $this->assertEquals($expected, (new ByActionClassNameResponderClassNameResolver())($actionClassName));
    }
}