<?php

namespace HydrefLab\Laravel\ADR\Action;

use HydrefLab\Laravel\ADR\Responder\ResponderInterface;

trait ReturnsResponderResponseTrait
{
    /**
     * Return action response.
     *
     * If action returns responder, call proper method to create response.
     *
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
