<?php

namespace HydrefLab\Laravel\ADR\Responder;

interface ResponderInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respond();
}