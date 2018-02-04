<?php

namespace HydrefLab\Laravel\ADR;

interface ResponderInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respond();
}