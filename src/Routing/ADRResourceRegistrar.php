<?php

namespace HydrefLab\Laravel\ADR\Routing;

use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Support\Str;

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
        $actionHandler = $this->getActionClassName(trim($namespace, '\\'), $method, $resource);

        $action = ['as' => $name, 'uses' => $actionHandler];

        if (isset($options['middleware'])) {
            $action['middleware'] = $options['middleware'];
        }

        return $action;
    }

    /**
     * @param string $namespace
     * @param string $method
     * @param string $resource
     * @return string
     */
    private function getActionClassName(string $namespace, string $method, string $resource): string
    {
        $resource = ('index' !== $method) ? Str::singular($resource) : Str::plural($resource);

        return (false === empty($namespace))
            ? sprintf('%s\%s%sAction', $namespace, ucfirst($method), ucfirst($resource))
            : sprintf('%s%sAction', ucfirst($method), ucfirst($resource));
    }
}