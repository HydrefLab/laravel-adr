<?php

namespace HydrefLab\Laravel\ADR;

use HydrefLab\Laravel\ADR\Routing\ADRResourceRegistrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Route;

class ADRRouteServiceProvider extends RouteServiceProvider
{
    /**
     * @return void
     */
    public function register()
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
}