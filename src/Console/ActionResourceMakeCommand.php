<?php

namespace HydrefLab\Laravel\ADR\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ActionResourceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:adr:action_resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD action (ADR) classes';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action Resource';

    /**
     * Default action types (correspond to resource controller methods).
     *
     * @var array
     */
    protected $actionTypes = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->getActionTypes() as $actionType) {
            $this->call('make:adr:action', [
                'name' => $this->getActionClass($this->argument('resource'), $actionType),
                '--responder' => $this->option('responder'),
                '--responder_type' => (true === $this->option('responder')) ? $this->option('type') : null,
            ]);
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return '';
    }

    /**
     * Parse resource name to produce action class name.
     *
     * @param string $resource
     * @param string $actionType
     * @return string
     */
    protected function getActionClass(string $resource, string $actionType): string
    {
        $resource = str_replace('/', '\\', $resource);
        $resource = explode('\\', $resource);

        $resourceName = ('index' !== $actionType) ? Str::singular(last($resource)) : Str::plural(last($resource));

        return sprintf(
            '%s\%s%sAction',
            implode('\\', array_slice($resource, 0, -1)),
            ucfirst($actionType),
            ucfirst($resourceName)
        );
    }

    /**
     * Get action types for generated resource.
     *
     * @return array
     */
    protected function getActionTypes()
    {
        if ('api' === $this->option('type')) {
            $this->actionTypes = ['index', 'show', 'store', 'update', 'destroy'];
        }

        if (false === is_null($this->option('only'))) {
            return array_intersect($this->actionTypes, explode(',', $this->option('only')));
        } elseif (false === is_null($this->option('except'))) {
            return array_diff($this->actionTypes, explode(',', $this->option('except')));
        }

        return $this->actionTypes;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Actions';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['resource', InputArgument::REQUIRED, 'The name of the resource'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Specify resource type.'],

            ['only', 'o', InputOption::VALUE_OPTIONAL, 'Set resource only actions.'],

            ['except', 'e', InputOption::VALUE_OPTIONAL, 'Set resource except actions.'],

            ['responder', 'r', InputOption::VALUE_NONE, 'Generate an action responder.'],
        ];
    }
}
