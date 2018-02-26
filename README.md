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
```php
php artisan vendor:publish
```
to create base action class in `app\Http\Actions` folder.

# Introduction

Some time ago, Mr. Paul M. Jones proposed an _Action Domain Responder (ADR)_ pattern, which is an alternative
to _Model View Controller (MVC)_ interface pattern. _ADR_ pattern is better suited for server-side applications
operating in the request/response concept.

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

In _ADR_ pattern, the action is responsible to call the domain and then pass eventual domain processing result to
the responder. So, basically, actions are one-method (thin) controllers, thus we can call it request handlers. Since
actions are thin controllers, it is quite handy to use them as invokable classes, which also simplifies testing.

Responder in _ADR_ is the presentation layer. Responder receives necessary data form the action and builds response that
is send back to the client. It is also possible, and sometimes handy, to include content negotiation within responder, 
i.e. prepare response in the format that suits the client best, based on request accept header.

# Usage

This package introduces base version of two components described above: an action and a responder.

## Conventions

I prefer _convention over configuration_ approach, therefore this package utilizes some conventions that come from Laravel.

These conventions are used by console generator command.

### Naming

All actions should have `Action` postfix added to the class (and file) name, for example `ShowUsersAction`.

All responder should have `Responder` postfix added to the class (and file) name, for example `ShowUsersActionResponder`.

### File placement

All actions should be placed in `app/Http/Actions` folder (similar to controllers).

All responders should be placed in `app/Http/Responders` folder.

## Action & Routing

### Action

Action in _ADR_ is responsible for handling request - call the domain, then call the responder and pass any required data.
This simplicity allows to model action as a invokable (callable) class.

```php
class ShowUserAction extends Action
{
    /**
     * @param mixed $id
     * @return mixed
     */
    public function __invoke(Request $request, $id)
    {
        $users = User::all();
        
        return responder($users); // or ResponderFactory::create($request, $users)
    }
}
```

Any action should extend `HydrefLab\Laravel\ADR\Action\Action` class. However, it is recommended that any action extends
`App\Http\Actions\Action` which is already extension of a base action class.

Any action should return either responder instance or already built response.

To create responder instance, `responder` helper method or static `ResponderFactory::create()` method can be used. 

### Routing

To register single action route, simply add:
```php
Route::get('users/{id}', \App\Http\Actions\ShowUserAction::class);
```
in your routes file.

Actions are thin controllers. One resource controller in Laravel corresponds to 7 (!) actions. Register routes for all
that actions can be quite bothersome. This package provides a mechanism to register your resource-like actions routes the 
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
```php
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

Basically, registration of actions routes is the same as any other route within Laravel.
 
## Responder

Responder in _ADR_ is responsible for building a response. Responder's constructor method should collect all required data
needed for creating a response.

```
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

One of the additional responsibilities of the responder might be content negotiation, i.e. preparing response in the format
that suits the client best. This is usually done based on `Accept` header in the HTTP request.

Small handy mechanism was added to handle content negotiation in an easy way. It is required for responder to extend
`HydrefLab\Laravel\ADR\Responder\Responder` abstract class. This class adds/uses functionality for determining the response's
format as well as provide two default methods for building most popular response types: html and json.

`HydrefLab\Laravel\ADR\Responder\Responder` class contains mapping between required format by the client and method that
should be called depending on that format. Of course, map as well as response creation methods are fully customizable.

## Action-Responder binding

Action and responder are two connected pieces. To bind those two together, simple auto-resolving mechanism was introduced.

### Auto-resolving

As mentioned, any action class should return either responder or response (ideally produced by a responder). To simplify
that, `responder` helper method was added. However, there is no configuration that will bind action and responder together.
For that, simple auto-resolving mechanism is used.

This package adds two default responder resolvers:
* first is based on action's class property `$responderClass`,
* second is based on action's class name.

During responder auto-resolving, responder class name is guessed based on corresponding action class name. First, we scan
action class for `$responderClass` property. It action class does not have that property or its value is null, then second
resolver is called - action class name is transformed into responder class name (thanks to the used convention).

### Extensions

If presented responder resolvers don't suit developer's need, there is a possibility to add new resolvers.

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

# Example