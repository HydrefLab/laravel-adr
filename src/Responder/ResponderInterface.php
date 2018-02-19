<?php

namespace HydrefLab\Laravel\ADR\Responder;

interface ResponderInterface
{
    /**
     * Create and return response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respond();
}