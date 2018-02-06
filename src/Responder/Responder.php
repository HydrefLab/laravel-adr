<?php

namespace HydrefLab\Laravel\ADR\Responder;

use Illuminate\Http\Request;

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
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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

        throw new \Exception();
    }

    /**
     * @return string
     */
    protected function getResponseFormat(): string
    {
        $format = $this->request->format(null);

        if (true === is_null($format)) {
            // check for other options
        }

        return $format ?? 'html';
    }
}