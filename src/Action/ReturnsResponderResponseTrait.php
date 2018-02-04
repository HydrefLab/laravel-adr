<?php

namespace HydrefLab\Laravel\ADR\Action;

use HydrefLab\Laravel\ADR\ResponderInterface;

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

        return $response;
    }
}
