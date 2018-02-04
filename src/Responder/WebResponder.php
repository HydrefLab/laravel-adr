<?php

namespace HydrefLab\Laravel\ADR;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class WebResponder implements ResponderInterface
{
    /**
     * @var ViewFactory
     */
    protected $viewFactory;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
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
     * @param ViewFactory $viewFactory
     * @param string $template
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param \Closure|null $callback
     */
    public function __construct(
        ViewFactory $viewFactory,
        string $template,
        array $data = [],
        $status = 200,
        $headers = [],
        \Closure $callback = null
    ) {
        $this->viewFactory = $viewFactory;
        $this->template = $template;
        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
        $this->callback = $callback;
    }

    /**
     * @return Response
     */
    public function respond()
    {
        return new Response(
            tap($this->viewFactory->make($this->template, $this->data), $this->getCallback()),
            $this->status,
            $this->headers
        );
    }

    /**
     * @return \Closure
     */
    protected function getCallback(): \Closure
    {
        if (true === is_null($this->callback)) {
            $this->callback = function (View $view) {};
        }

        return $this->callback;
    }
}