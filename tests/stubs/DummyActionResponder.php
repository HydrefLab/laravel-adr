<?php

namespace HydrefLab\Laravel\ADR\Tests\stubs;

use HydrefLab\Laravel\ADR\Responder\ResponderInterface;
use Illuminate\Http\Request;

class DummyActionResponder implements ResponderInterface
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
     * @var mixed
     */
    protected $status;

    /**
     * @param Request $request
     * @param $data
     * @param $status
     */
    public function __construct(Request $request, $data, $status)
    {
        $this->request = $request;
        $this->data = $data;
        $this->status = $status;
    }
    /**
     * @return mixed
     */
    public function respond()
    {
        return [$this->data, $this->status];
    }
}