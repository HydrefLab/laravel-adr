<?php

namespace HydrefLab\Laravel\ADR\Responder;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

abstract class Responder implements ResponderInterface
{
    /**
     * @var array
     */
    protected $map = [
        'html' => 'respondWithHtml',
        'json' => 'respondWithJson',
    ];

    /**
     * @var array
     */
    protected $additionalFormats = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var mixed
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
     * @var string
     *
     */
    protected $viewTemplate;

    /**
     * @param Request $request
     * @param $data
     * @param int $status
     * @param array $headers
     * @param \Closure $callback
     */
    public function __construct(Request $request, $data, int $status = 200, array $headers = [], \Closure $callback = null)
    {
        $this->request = $request;

        foreach ($this->additionalFormats as $format => $mimeTypes) {
            $this->request->setFormat($format, $mimeTypes);
        }

        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
        $this->callback = $callback ?? function (View $view) {};
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function respond(): \Symfony\Component\HttpFoundation\Response
    {
        $format = $this->getResponseFormat();
        $method = $this->map[$format];

        if (true === method_exists($this, $method)) {
            return $this->{$method}();
        }

        throw new \Exception("Cannot create response. Method $method is missing in {$this->getClassName()} responder.");
    }

    /**
     * @return string
     */
    protected function getResponseFormat(): string
    {
        return $this->request->format();
    }

    /**
     * @return string
     */
    protected function getClassName(): string
    {
        return class_basename($this);
    }

    /**
     * @return Response
     */
    protected function respondWithHtml(): Response
    {
        return new Response(
            tap(view($this->viewTemplate, $this->data), $this->callback),
            $this->status,
            $this->headers
        );
    }

    /**
     * @return JsonResponse
     */
    protected function respondWithJson(): JsonResponse
    {
        return (true === $this->data instanceof Responsable)
            ? $this->data->toResponse($this->request)
            : new JsonResponse($this->data, $this->status, $this->headers);
    }
}