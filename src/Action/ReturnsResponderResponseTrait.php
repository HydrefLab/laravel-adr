<?php

namespace HydrefLab\Laravel\ADR\Action;

use HydrefLab\Laravel\ADR\Responder\ResponderInterface;
use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use Illuminate\Container\Container;

trait ReturnsResponderResponseTrait
{
    /**
     * @param string $method
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $response = call_user_func_array([$this, $method], $parameters);

        if (true === $response instanceof ResponderInterface) {
            return $response->respond();
        }

        $responder = ResponderResolver::resolve(get_class($this), Container::getInstance()->make('request'), $response);

        return $responder->respond();
    }
}
