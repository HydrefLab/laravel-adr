<?php

namespace HydrefLab\Laravel\ADR\Console;

use HydrefLab\Laravel\ADR\Action\ActionResolver;
use Illuminate\Console\GeneratorCommand;
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
                'name' => $this->getActionClassName($this->argument('name'), $actionType),
                '--responder' => $this->option('responder'),
                '--responder_type' => (true === $this->option('responder')) ? $this->option('responder_type') : null,
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
    protected function getActionClassName(string $resource, string $actionType): string
    {
        $resource = explode('\\', str_replace('/', '\\', $resource));

        return ActionResolver::resolveClassName(
            implode('\\', array_slice($resource, 0, -1)),
            last($resource),
            $actionType
        );
    }

    /**
     * Get action types for generated resource.
     *
     * @return array
     */
    protected function getActionTypes()
    {
        if (true === $this->option('api')) {
            $this->actionTypes = ['index', 'show', 'store', 'update', 'destroy'];
        }

        if (!is_null($this->option('only'))) {
            return array_intersect($this->actionTypes, explode(',', $this->option('only')));
        } elseif (!is_null($this->option('except'))) {
            return array_diff($this->actionTypes, explode(',', $this->option('except')));
        }

        return $this->actionTypes;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource'],
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
            ['api', 'a', InputOption::VALUE_NONE, 'Generate api resource.'],

            ['only', 'o', InputOption::VALUE_OPTIONAL, 'Set resource \'only\' actions.'],

            ['except', 'e', InputOption::VALUE_OPTIONAL, 'Set resource \'except\' actions.'],

            ['responder', 'r', InputOption::VALUE_NONE, 'Generate an action responder.'],

            ['responder_type', 't', InputOption::VALUE_OPTIONAL, 'Set action responder type (plain or extended).']
        ];
    }
}
