<?php

namespace HydrefLab\Laravel\ADR\Tests;

use HydrefLab\Laravel\ADR\Action\ActionResolver;
use PHPUnit\Framework\TestCase;

class ActionResolverTest extends TestCase
{
    public function actionClassNamePartsProvider()
    {
        return [
            ['', 'Users', 'index', 'IndexUsersAction'],
            ['', 'Users', 'Index', 'IndexUsersAction'],
            ['', 'users', 'index', 'IndexUsersAction'],
            ['', 'users', 'Index', 'IndexUsersAction'],
            ['', 'Users', 'destroy', 'DestroyUserAction'],
            ['', 'Users', 'Destroy', 'DestroyUserAction'],
            ['', 'users', 'destroy', 'DestroyUserAction'],
            ['', 'users', 'Destroy', 'DestroyUserAction'],
            ['DummyNamespace', 'Users', 'destroy', 'DummyNamespace\DestroyUserAction'],
            ['Dummy\Namespace', 'Users', 'destroy', 'Dummy\Namespace\DestroyUserAction'],
            ['\Dummy\Namespace', 'Users', 'destroy', 'Dummy\Namespace\DestroyUserAction'],
        ];
    }

    /**
     * @dataProvider actionClassNamePartsProvider
     * @param string $namespace
     * @param string $resource
     * @param string $actionType
     * @param string $expected
     * @return void
     */
    public function testResolveActionClassName(string $namespace, string $resource, string $actionType, string $expected)
    {
        $this->assertEquals($expected, ActionResolver::resolveClassName($namespace, $resource, $actionType));
    }
}