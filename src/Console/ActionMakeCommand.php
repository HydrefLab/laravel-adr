<?php

namespace HydrefLab\Laravel\ADR\Console;

use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ActionMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:adr:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new action (ADR) class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action';

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
        if (true === $this->option('responder')) {
            $this->createResponder();
        }

        parent::handle();
    }

    /**
     * Call responder generator command.
     *
     * @return void
     */
    protected function createResponder()
    {
        $this->call('make:adr:responder', [
            'name' => ResponderResolver::resolveClassName($this->argument('name')),
            '--type' => $this->option('responder_type')
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $actionType = $this->getActionType();

        return (false === is_null($actionType))
            ? __DIR__ . "/stubs/action/$actionType.stub"
            : __DIR__ . '/stubs/action.stub';
    }

    /**
     * Determine action type in order to use proper stub file.
     *
     * @return null|string
     */
    protected function getActionType()
    {
        foreach ($this->actionTypes as $type) {
            $strpos = stripos($this->argument('name'), $type);

            if (false !== $strpos) {
                return mb_strtolower(substr($this->argument('name'), $strpos, strlen($type)));
            }
        }

        return null;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Actions';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['responder', 'r', InputOption::VALUE_NONE, 'Generate an action responder.'],

            ['responder_type', 't', InputOption::VALUE_OPTIONAL, 'Generated action responder type.'],
        ];
    }
}
