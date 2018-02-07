<?php

namespace HydrefLab\Laravel\ADR\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ResponderMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'adr:make:responder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new responder (ADR) class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Responder';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return (false === is_null($this->option('type')) && true === in_array($this->option('type'), ['api', 'web']))
            ? __DIR__ . "/stubs/responder/{$this->option('type')}.stub"
            : __DIR__ . '/stubs/responder.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Responders';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Responder type (api or web).'],
        ];
    }
}
