<?php

namespace HydrefLab\Laravel\ADR;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ApiResponder implements ResponderInterface
{
    /**
     * @var array|Responsable|mixed
     */
    protected $data;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var \Closure|null
     */
    protected $callback;

    /**
     * @param Responsable|array|mixed $data
     * @param int $status
     * @param array $headers
     * @param \Closure|null $callback
     */
    public function __construct($data = [], int $status = 200, array $headers = [], \Closure $callback = null)
    {
        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
        $this->callback = $callback;
    }

    /**
     * @return JsonResponse
     */
    public function respond(): JsonResponse
    {
        $response = (true === $this->data instanceof Responsable)
            ? $this->data->toResponse(Container::getInstance()->make('request'))
            : new JsonResponse($this->data, $this->status, $this->headers);

        return tap($response, $this->getCallback());
    }

    /**
     * @return \Closure
     */
    protected function getCallback(): \Closure
    {
        if (true === is_null($this->callback)) {
            $this->callback = function (JsonResponse $response) {};
        }

        return $this->callback;
    }
}