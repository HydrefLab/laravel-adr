<?php

namespace HydrefLab\Laravel\ADR\Console;

use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use Symfony\Component\Console\Input\InputOption;

class ResponderResourceMakeCommand extends ActionResourceMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:adr:responder_resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD responder (ADR) class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Responder';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->getActionTypes() as $actionType) {
            $this->call('make:adr:responder', [
                'name' => $this->getResponderClassName($this->argument('name'), $actionType),
                '--type' => $this->option('type'),
            ]);
        }
    }

    /**
     * @param string $resource
     * @param string $actionType
     * @return string
     */
    protected function getResponderClassName(string $resource, string $actionType): string
    {
        return ResponderResolver::resolveClassName($this->getActionClassName($resource, $actionType));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['api', 'a', InputOption::VALUE_NONE, 'Generate api resource responders.'],

            ['only', 'o', InputOption::VALUE_OPTIONAL, 'Set resource \'only\' responders.'],

            ['except', 'e', InputOption::VALUE_OPTIONAL, 'Set resource \'except\' responders.'],

            ['type', 't', InputOption::VALUE_OPTIONAL, 'Responder type (plain or extended).'],
        ];
    }
}
