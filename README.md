# Action-Domain-Responder (ADR) implementation for Laravel

* Installation
* Introduction
    * ADR
* Usage 

# Installation

```bash
composer require hydreflab/laravel-adr
```

Package requires Laravel >= 5.5. 

No additional service provider registration is required as package uses auto-discovery feature.

After, run:
```
php artisan vendor:publish
```
to create base action class in `app\Http\Actions` folder.

# Introduction

Some time ago, Mr. Paul M. Jones proposed an _Action Domain Responder (ADR)_ pattern, which is an alternative
to _Model View Controller (MVC)_ interface pattern. _ADR_ pattern is better suited for server-side applications
operating with the request/response concept.

_ADR_ pattern is described [here](https://github.com/pmjones/adr).

## ADR

_ADR_ introduces objects/responsibilities that correspond to the ones from _MVC_. However, directions of 
communication are a bit different.

_ADR_ defines three components:
* action (you can think of it as a controller or a request handler),
* domain (business logic),
* responder (element that is responsible for building response).

Domain part is related to the business logic and it is only developer's responsibility how to model and use that,
if it is either strict DDD approach (repositories, use cases and everything) or typical Laravel models, or some service 
layer or something else.

Action is responsible to call the domain and then pass eventual domain processing result to the responder. So, 
basically, actions are one-method (thin) controllers, thus we can call it request handlers. Since they are thin 
controllers, it is quite handy to use them as invokable classes, which also simplifies testing.

Responder in _ADR_ is the presentation layer. Responder receives necessary data form the action and builds response that
is send back to the client. It is also possible, and sometimes handy, to include content negotiation within responder, 
i.e. prepare response in the format that suits the client best, based on request `Accept` header.

# Usage

This package introduces base version of two components described above: an action and a responder.

## Conventions

I prefer _convention over configuration_ approach, therefore this package utilizes some conventions that come from
Laravel.

**Presented conventions are used by routing and console elements.**

### Naming

All actions should have `Action` postfix added to the class name, for example `ShowUserAction`.

All responders should have `Responder` postfix added to the class name, for example `ShowUserActionResponder`.

### File placement

All actions should be placed in `app/Http/Actions` folder.

All responders should be placed in `app/Http/Responders` folder.

## Action & Routing

### Action

Action in _ADR_ is responsible for handling request - call the domain, then call the responder and pass any required 
data. This simplicity allows to model action as a invokable (callable) class.

```php
namespace App\Http\Actions;

class ShowUserAction extends Action
{
    /**
     * @param mixed $id
     * @return mixed
     */
    public function __invoke(Request $request, $id)
    {
        $user = User::find($id);
        
        return responder($user); // or ResponderFactory::create($request, $user)
    }
}
```

Any action should extend `HydrefLab\Laravel\ADR\Action\Action` class. 

However, it is recommended that action extends `App\Http\Actions\Action` class which is already an extension of a 
base action class. This is situation similar to controllers - typically new controllers extend 
`App\Http\Controllers\Controller` class and not its parent `Illuminate\Routing\Controller` class.

Any action should return either responder instance or already built response. To create responder instance, `responder`
helper method or static `ResponderFactory::create()` method can be used. 

### Routing

To register single action route, simply add:
```php
Route::get('users/{id}', \App\Http\Actions\ShowUserAction::class);
```
in your routes file.

#### Resource actions

Actions are thin controllers. One resource controller in Laravel corresponds to 7 (!) actions. Register routes for all
actions can be quite bothersome. This package provides a mechanism to register your resource-like actions routes in the 
same way as registering resource controller.

Let's assume that we have `Users` resource actions:
* `CreateUserAction`,
* `DestroyUserAction`,
* `EditUserAction`,
* `IndexUsersAction`,
* `ShowUserAction`,
* `StoreUserAction`,
* `UpdateUserAction`.

In your routes file, simply add:
```php
Route::adrResource('users');
Route::adrApiResource('users'); // for API-like resource
```

This will produce following result:
```
+--------+-----------+-------------------+---------------+------------------------------------+------------+
| Domain | Method    | URI               | Name          | Action                             | Middleware |
+--------+-----------+-------------------+---------------+------------------------------------+------------+
|        | GET|HEAD  | users             | users.index   | App\Http\Actions\IndexUsersAction  | web        |
|        | POST      | users             | users.store   | App\Http\Actions\StoreUserAction   | web        |
|        | GET|HEAD  | users/create      | users.create  | App\Http\Actions\CreateUserAction  | web        |
|        | GET|HEAD  | users/{user}      | users.show    | App\Http\Actions\ShowUserAction    | web        |
|        | PUT|PATCH | users/{user}      | users.update  | App\Http\Actions\UpdateUserAction  | web        |
|        | DELETE    | users/{user}      | users.destroy | App\Http\Actions\DestroyUserAction | web        |
|        | GET|HEAD  | users/{user}/edit | users.edit    | App\Http\Actions\EditUserAction    | web        |
+--------+-----------+-------------------+---------------+------------------------------------+------------+
```

`adrResource` and `adrApiResource` are route macros and you can use them in the same way as you can use resource 
controller routes registration, i.e. pass namespace or additional route options:
```php
// controller approach
Route::resource('users', 'MyNamespace/UsersController', ['except' => ['destroy']]);

// action approach
Route::adrResource('users', 'MyNamespace', ['except' => ['destroy']]);
```
The only difference here is that in controller approach you pass namespace or partial namespace of the controller, while
in action approach you specify namespace or partial namespace of the all actions.

Above functionality uses mentioned conventions. If you are planning to place or name your actions differently, you will
need to register your own action class name resolver (to be implemented).

## Responder

Responder in _ADR_ is responsible for building the response. Responder's constructor method should collect all required 
data needed for creating a response.

```php
class ShowUserActionResponder implements ResponderInterface
{
    /**
     * @var mixed
     */
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return Response
     */
    public function respond(): Response
    {
        return new Response(view('users.show', $this->data))
    }
}
```

Any responder must implement `ResponderInterface` interface.

### Content negotiation

One of the additional responsibilities of the responder might be content negotiation.

Small handy mechanism was added to handle content negotiation in an easy way. It is required for the responder to extend
`HydrefLab\Laravel\ADR\Responder\Responder` abstract class. This class adds/uses functionality for determining the 
response's format as well as provide two default methods for building most popular response types: html and json.

`HydrefLab\Laravel\ADR\Responder\Responder` class contains mapping between required format by the client and method that
should be called depending on that format. Of course, map as well as response creation methods are fully customizable.

## Action-Responder binding

Action and responder are two connected pieces. To bind those two together, simple auto-resolving mechanism was 
introduced.

### Auto-resolving

As mentioned, any action class should return either responder or response (ideally produced by a responder). To simplify
that, `responder` helper method was added. However, there is no configuration that will bind action and responder 
together. For that, simple auto-resolving mechanism is used.

Package adds two default responder's class name resolvers:
* first is based on action's class property `$responderClass`,
* second is based on action's class name.

During responder auto-resolving, responder's class name is guessed based on corresponding action's class name. First, 
we scan action class for `$responderClass` property. It action class does not have that property or its value is null, 
then second resolver is called - action's class name is transformed into responder's class name (thanks to the used 
convention). Having responder's class name, its instance is created.

### Extensions

If default responder's class name resolvers don't suit developer's need, there is a possibility to add new resolvers.

It is possible by calling `ResponderResolver::extend()` method in any of your application service providers:
```php
ResponderResolver::extend(new MyCustomResponderResolver());

// or

ResponderResolver::extend(function($actionClassName) {
    // my custom resolver content here
});
```

Resolvers are run in the reverse order as they were added.
 
## Console

Package adds 4 console commands for generating actions and responders.

### `artisan make:adr:action`

Running `php artisan make:adr:action MyAwesomeAction` will create new `MyAwesomeAction` class in `app/Http/Actions` 
directory.
 
Command takes one argument:
* `name` - action's class name.
 
Command takes two options:
* `-r` or `--responder` - flag to generate responder class along with the action class (giving example above,
`MyAwesomeActionResponder` class will be generated in `app/Http/Responders` directory; action class will have 
`$responderClass` property automatically set),
* `-t` or `--responder_type` - flag to indicate responder's type: `api` or `web`.

### `artisan make:adr:action_resource`

Running `php artisan make:adr:action_resource Users` will create resource-like actions (5 or 7). All classes will be 
generated in `app/Http/Actions` directory.

Command takes one argument:
* `name` - resource name (with namespace).

Command takes four options:
* `-r` or `--responder` - flag to generate responder classes along with actions (it will behave in the same way as 
command above, i.e. generate set or responders and bind them with controller via class property),
* `-t` or `--responder_type` - flag to indicate responders type: `api` or `web`,
* `-o` or `--only` - flag to set which resource type to generate (similar to route options),
* `-e` or `--except` - flag to set which resource type not to generate (similar to route options).

Example:
```
php artisan make:adr:action_resource Users -r -t=api
```
will generate 5 action classes and 5 responders classes:
* `DestroyUserAction`, `IndexUsersAction`, `ShowUserAction`, `StoreUserAction`, `UpdateUserAction` (all in `app/Http/Actions`),
* `DestroyUserActionResponder`, `IndexUsersActionResponder`, `ShowUserActionResponder`, `StoreUserActionResponder`, 
`UpdateUserActionResponder` (all in `app/Http/Responders`).

### `artisan make:adr:responder`

Running `php artisan make:adr:responder MyAwesomeActionResponder` will create new `MyAwesomeActionResponder` class in
`app/Http/Responders` directory.

Command takes one argument:
* `name` - responder's class name.
 
Command takes one option:
* `-t` or `--type` - flag to indicate responder's type: `api` or `web`.

### `artisan make:adr:responder_resource`

Running `php artisan make:adr:responder_resource Users` will create resource-like responders (5 or 7). All classes will be 
generated in `app/Http/Responders` directory.

Command takes one argument:
* `name` - resource name (with namespace).

Command takes three options:
* `-t` or `--type` - flag to indicate responders type: `api` or `web`,
* `-o` or `--only` - flag to set which resource type to generate (similar to route options),
* `-e` or `--except` - flag to set which resource type not to generate (similar to route options).

# Example