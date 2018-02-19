<?php

namespace HydrefLab\Laravel\ADR\Responder;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class Responder implements ResponderInterface
{
    /**
     * Map between response expected data format and method for response creation.
     *
     * Map is an array where keys are expected data formats, and values are method
     * names that should be executed in order to create response.
     *
     * @var array
     */
    protected $responseFormatMap = [
        'html' => 'html',
        'json' => 'json',
    ];

    /**
     * Additional data formats expected in the response.
     *
     * @see \Symfony\Component\HttpFoundation\Request::initializeFormats() for
     * initial formats handled by request.
     *
     * @var array
     */
    protected $additionalFormats = [];

    /**
     * Request instance.
     *
     * @var Request
     */
    protected $request;

    /**
     * Response data.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Response status.
     *
     * @var int
     */
    protected $status;

    /**
     * Response headers.
     *
     * @var array
     */
    protected $headers;

    /**
     * Callback for modifying View instance.
     *
     * @var \Closure|null
     */
    protected $callback;

    /**
     * Template name for html-like responses.
     *
     * @var string
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
     * Create new response.
     *
     * Before creating response, content negotiation is performed to determine the best
     * available data format expected by the client.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function respond(): \Symfony\Component\HttpFoundation\Response
    {
        $format = $this->getResponseFormat();
        $method = $this->responseFormatMap[$format];

        if (true === method_exists($this, $method)) {
            return $this->{$method}();
        }

        throw new \Exception("Cannot create response. Method $method is missing in {$this->getClassName()} responder.");
    }

    /**
     * Get the data format expected in the response.
     *
     * 'html' format is returned as default.
     *
     * @return string
     */
    protected function getResponseFormat(): string
    {
        return $this->request->format();
    }

    /**
     * Get responder class name.
     *
     * @return string
     */
    protected function getClassName(): string
    {
        return class_basename($this);
    }

    /**
     * Create html-like response.
     *
     * @return Response
     */
    protected function html(): Response
    {
        $view = (true === $this->data instanceof View)
            ? $this->data
            : view($this->viewTemplate, $this->data);

        return new Response(tap($view, $this->callback), $this->status, $this->headers);
    }

    /**
     * Create json-like response.
     *
     * @return JsonResponse
     */
    protected function json(): JsonResponse
    {
        return (true === $this->data instanceof Responsable)
            ? $this->data->toResponse($this->request)
            : new JsonResponse($this->data, $this->status, $this->headers);
    }
}