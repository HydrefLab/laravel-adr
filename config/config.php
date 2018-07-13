<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Actions and Responders Namespaces
    |--------------------------------------------------------------------------
    |
    | You may specify the namespaces for ADR actions and responders. These
    | namespaces are used in actions and responders classes generators.
    | They are also used by default actions and responders resolvers.
    |
    */

    'namespace' => [

        'actions' => 'App\Http\Actions',

        'responders' => 'App\Http\Responders',

    ],

    /*
    |--------------------------------------------------------------------------
    | Actions and Responders Postfixes
    |--------------------------------------------------------------------------
    |
    | You may specify the postfixes for ADR actions and responders. These
    | postfixes are used in actions and responders classes generators.
    | They are also used by default actions and responders resolvers.
    |
    */

    'postfix' => [

        'actions' => 'Action',

        'responders' => 'Responder',

    ],

];