<?php

namespace HydrefLab\Laravel\ADR\Routing;

use HydrefLab\Laravel\ADR\Action\ActionResolver;
use Illuminate\Routing\ResourceRegistrar;

class ADRResourceRegistrar extends ResourceRegistrar
{
    /**
     * Get the action array for a resource route.
     *
     * @param string $resource
     * @param string $namespace
     * @param string $method
     * @param array $options
     * @return array
     */
    protected function getResourceAction($resource, $namespace, $method, $options)
    {
        $name = $this->getResourceRouteName($resource, $method, $options);
        $actionHandler = ActionResolver::resolveClassName(trim($namespace, '\\'), $resource, $method);

        $action = ['as' => $name, 'uses' => $actionHandler];

        if (isset($options['middleware'])) {
            $action['middleware'] = $options['middleware'];
        }

        return $action;
    }
}