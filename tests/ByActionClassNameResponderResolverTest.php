<?php

namespace HydrefLab\Laravel\ADR\Tests;

use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionClassNameResponderResolver;
use PHPUnit\Framework\TestCase;

class ByActionClassNameResponderResolverTest extends TestCase
{
    public function actionClassNameProvider()
    {
        return [
            ['DummyAction', 'DummyActionResponder'],
            ['DummyNamespace\DummyAction', 'DummyNamespace\DummyActionResponder'],
            ['DummyNamespace\Actions\DummyAction', 'DummyNamespace\Responders\DummyActionResponder'],
            ['DummyNamespace\Actions\DummyModule\Actions\DummyAction', 'DummyNamespace\Responders\DummyModule\Responders\DummyActionResponder'],
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
        $this->assertEquals($expected, (new ByActionClassNameResponderResolver())($actionClassName));
    }
}