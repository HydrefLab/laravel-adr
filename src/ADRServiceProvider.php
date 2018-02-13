<?php

namespace HydrefLab\Laravel\ADR;

use HydrefLab\Laravel\ADR\Console\ActionMakeCommand;
use HydrefLab\Laravel\ADR\Console\ActionResourceMakeCommand;
use HydrefLab\Laravel\ADR\Console\ResponderMakeCommand;
use HydrefLab\Laravel\ADR\Console\ResponderResourceMakeCommand;
use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionClassNameResponderResolver;
use HydrefLab\Laravel\ADR\Responder\Resolver\ByAttributeResponderResolver;
use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use HydrefLab\Laravel\ADR\Routing\ADRResourceRegistrar;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ADRServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->commands([
            ActionMakeCommand::class,
            ActionResourceMakeCommand::class,
            ResponderMakeCommand::class,
            ResponderResourceMakeCommand::class,
        ]);
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->addAdrResourceRouteMacro();
        $this->extendResponderResolver();
    }

    /**
     * @return void
     */
    protected function addAdrResourceRouteMacro()
    {
        Route::macro('adrResource', function (string $name, $namespace = '', array $options = []) {
            if (func_num_args() === 2 && true === is_array(func_get_arg(1))) {
                $options = func_get_arg(1);
                $namespace = '';
            }

            return new PendingResourceRegistration(
                new ADRResourceRegistrar($this), $name, $namespace, $options
            );
        });
    }

    /**
     * @return void
     */
    protected function extendResponderResolver()
    {
        ResponderResolver::extend(new ByActionClassNameResponderResolver());
        ResponderResolver::extend(new ByAttributeResponderResolver());
    }
}