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
    protected $name = 'make:adr:responder';

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
        $type = (!is_null($this->option('type')) && in_array($this->option('type'), ['plain', 'extended']))
            ? $this->option('type')
            : 'plain';

        return __DIR__ . "/stubs/responder/{$type}.stub";
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
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Responder type (plain or extended).'],
        ];
    }
}
