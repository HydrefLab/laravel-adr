<?php

namespace HydrefLab\Laravel\ADR\Tests\stubs;

use HydrefLab\Laravel\ADR\Responder\ResponderInterface;
use Illuminate\Http\Request;

class DummyResponderActionResponder implements ResponderInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param Request $request
     * @param $data
     */
    public function __construct(Request $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function respond()
    {
        return $this->data;
    }
}