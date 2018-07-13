<?php

namespace HydrefLab\Laravel\ADR;

use HydrefLab\Laravel\ADR\Action\ActionResolver;
use HydrefLab\Laravel\ADR\Action\Resolver\DefaultActionClassNameResolver;
use HydrefLab\Laravel\ADR\Console\ActionMakeCommand;
use HydrefLab\Laravel\ADR\Console\ActionResourceMakeCommand;
use HydrefLab\Laravel\ADR\Console\ResponderMakeCommand;
use HydrefLab\Laravel\ADR\Console\ResponderResourceMakeCommand;
use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionClassNameResponderClassNameResolver;
use HydrefLab\Laravel\ADR\Responder\Resolver\ByActionPropertyResponderClassNameResolver;
use HydrefLab\Laravel\ADR\Responder\ResponderResolver;
use HydrefLab\Laravel\ADR\Routing\ADRResourceRegistrar;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ADRServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../stubs/Action.stub' => app_path('Http/Actions/Action.php')
            ]);

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('adr.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->addAdrResourceRouteMacros();
        $this->extendActionResolver();
        $this->extendResponderResolver();

        $this->commands([
            ActionMakeCommand::class,
            ActionResourceMakeCommand::class,
            ResponderMakeCommand::class,
            ResponderResourceMakeCommand::class,
        ]);
    }

    /**
     * Register Router macros for ADR.
     *
     * @return void
     */
    protected function addAdrResourceRouteMacros()
    {
        Route::macro('adrResource', function (string $name, $namespace = '', array $options = []) {
            if (func_num_args() === 2 && true === is_array(func_get_arg(1))) {
                $options = func_get_arg(1);
                $namespace = '';
            }

            if (empty($namespace)) {
                $namespace = config('adr.namespace.actions', '');
            }

            return new PendingResourceRegistration(
                new ADRResourceRegistrar($this), $name, $namespace, $options
            );
        });

        Route::macro('apiAdrResource', function (string $name, $namespace = '', array $options = []) {
            return $this->adrResource($name, $namespace, array_merge([
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
            ], $options));
        });

        Route::macro('applyDefaultAdrMiddleware', function (bool $applyDefaultMiddleware = true) {
            $this->container->instance('middleware.applyDefaultForAdr', $applyDefaultMiddleware);
        });
    }

    /**
     * Extend action resolver with default resolvers.
     *
     * @return void
     */
    protected function extendActionResolver()
    {
        ActionResolver::extend(new DefaultActionClassNameResolver());
    }

    /**
     * Extend responder resolver with default resolvers.
     *
     * @return void
     */
    protected function extendResponderResolver()
    {
        ResponderResolver::extend(new ByActionClassNameResponderClassNameResolver());
        ResponderResolver::extend(new ByActionPropertyResponderClassNameResolver());
    }
}